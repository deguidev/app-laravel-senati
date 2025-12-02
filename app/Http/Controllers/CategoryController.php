<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ]);
    }

    /**
     * Export categories to PDF.
     */
    public function exportPdf()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        
        $pdf = Pdf::loadView('categories.pdf', compact('categories'));
        
        // Configurar el PDF
        $pdf->setPaper('a4', 'landscape'); // Orientación horizontal para mejor visualización de la tabla
        
        return $pdf->download('categorias_' . date('Y-m-d_His') . '.pdf');
    }

    /**
     * Export categories to Excel.
     */
    public function exportExcel()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        
        // Crear nuevo archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nombre');
        $sheet->setCellValue('C1', 'Descripción');
        $sheet->setCellValue('D1', 'Estado');
        $sheet->setCellValue('E1', 'Fecha Creación');
        
        // Estilo para encabezados
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        
        // Datos
        $row = 2;
        foreach ($categories as $category) {
            $sheet->setCellValue('A' . $row, $category->id);
            $sheet->setCellValue('B' . $row, $category->name);
            $sheet->setCellValue('C' . $row, $category->description ?? '');
            $sheet->setCellValue('D' . $row, $category->active ? 'Activo' : 'Inactivo');
            $sheet->setCellValue('E' . $row, $category->created_at->format('d/m/Y H:i'));
            $row++;
        }
        
        // Ajustar ancho de columnas
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generar archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'categorias_' . date('Y-m-d_His') . '.xlsx';
        $tempFile = storage_path('app/' . $filename);
        
        $writer->save($tempFile);
        
        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
