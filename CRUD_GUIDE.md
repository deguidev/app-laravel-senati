# 📚 Guía Completa: CRUD con Laravel 12 + Inertia + Vue 3

## 🎯 Índice
- [CRUD con Axios](#crud-con-axios)
- [CRUD con Inertia](#crud-con-inertia)
- [Diferencias Clave](#diferencias-clave)
- [Comandos Útiles](#comandos-útiles)

---

## 🔵 CRUD con Axios (Ejemplo: Categorías)

### Paso 1: Crear Migración
```bash
php artisan make:migration create_productos_table
```

### Paso 2: Editar Migración
**Archivo:** `database/migrations/XXXX_create_productos_table.php`
```php
$table->string('nombre');
$table->text('descripcion')->nullable();
$table->decimal('precio', 10, 2);
$table->boolean('activo')->default(true);
```

### Paso 3: Crear Modelo
**Archivo:** `app/Models/Producto.php`
```php
protected $fillable = ['nombre', 'descripcion', 'precio', 'activo'];
protected $casts = ['activo' => 'boolean', 'precio' => 'decimal:2'];
```

### Paso 4: Crear Controlador
**Archivo:** `app/Http/Controllers/ProductoController.php`
- Métodos: `index()`, `store()`, `show()`, `update()`, `destroy()`
- Retornar: `response()->json(['success' => true, 'data' => $data])`

### Paso 5: Agregar Rutas
**Archivo:** `routes/web.php`
```php
// Vista
Route::get('productos', fn() => Inertia::render('Productos/Index'))
    ->middleware(['auth', 'verified'])->name('productos');

// API
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('productos-data', [ProductoController::class, 'index']);
    Route::post('productos-data', [ProductoController::class, 'store']);
    Route::put('productos-data/{producto}', [ProductoController::class, 'update']);
    Route::delete('productos-data/{producto}', [ProductoController::class, 'destroy']);
});
```

### Paso 6: Crear Componente Vue
**Archivo:** `resources/js/pages/Productos/Index.vue`
- Importar: `axios from '@/lib/axios'`
- Funciones: `fetchProductos()`, `saveProducto()`, `deleteProducto()`
- Usar modal para crear/editar

### Paso 7: Agregar al Menú
**Archivo:** `resources/js/components/AppSidebar.vue`
```typescript
{ title: 'Productos', href: '/productos', icon: Package }
```

---

## 🟢 CRUD con Inertia (Ejemplo: Marcas)

### Paso 1: Crear Migración
```bash
php artisan make:migration create_clientes_table
```

### Paso 2: Crear Modelo
**Archivo:** `app/Models/Cliente.php`
```php
protected $fillable = ['nombre', 'email', 'telefono', 'activo'];
```

### Paso 3: Crear Controlador
**Archivo:** `app/Http/Controllers/ClienteController.php`
- Métodos: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
- Retornar: `Inertia::render('Clientes/Index', ['clientes' => $clientes])`

### Paso 4: Agregar Rutas Resource
**Archivo:** `routes/web.php`
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('clientes', ClienteController::class);
});
```

### Paso 5: Crear 3 Componentes Vue
1. **Index.vue** - Listado con `Link` y `router.delete()`
2. **Create.vue** - Formulario con `useForm()` y `form.post()`
3. **Edit.vue** - Formulario con `useForm()` y `form.put()`

### Paso 6: Agregar al Menú
```typescript
{ title: 'Clientes', href: '/clientes', icon: Users }
```

---

## ⚖️ Diferencias Clave

| Aspecto | Axios | Inertia |
|---------|-------|---------|
| **Archivos Vue** | 1 (con modal) | 3 (páginas separadas) |
| **Controlador** | JSON response | Inertia::render() |
| **Rutas** | Individuales | Route::resource() |
| **Navegación** | Sin navegación | Link + router |
| **Formularios** | ref() manual | useForm() |

---

## 🛠️ Comandos Útiles

```bash
# Migraciones
php artisan make:migration create_tabla_table
php artisan migrate
php artisan migrate:rollback

# Ver rutas
php artisan route:list
php artisan route:list --path=productos

# Desarrollo
npm run dev
php artisan serve
```

---

## 📁 Estructura de Archivos

### Con Axios (1 componente)
```
├── database/migrations/XXXX_create_productos_table.php
├── app/Models/Producto.php
├── app/Http/Controllers/ProductoController.php
├── routes/web.php
└── resources/js/pages/Productos/Index.vue
```

### Con Inertia (3 componentes)
```
├── database/migrations/XXXX_create_clientes_table.php
├── app/Models/Cliente.php
├── app/Http/Controllers/ClienteController.php
├── routes/web.php
└── resources/js/pages/Clientes/
    ├── Index.vue
    ├── Create.vue
    └── Edit.vue
```

---

## 🎨 Iconos Disponibles (Lucide)

```typescript
import { 
    Package, Users, Tag, ShoppingCart, 
    FileText, Settings, Home, LayoutGrid 
} from 'lucide-vue-next';
```

---

## ✅ Checklist para Nuevo CRUD

- [ ] Crear migración
- [ ] Crear modelo con $fillable
- [ ] Crear controlador
- [ ] Agregar rutas en web.php
- [ ] Crear componente(s) Vue
- [ ] Agregar al menú (AppSidebar.vue)
- [ ] Ejecutar `php artisan migrate`
- [ ] Probar en navegador
