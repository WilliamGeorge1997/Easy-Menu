# AGENTS.md — Laravel Coding Standards & Patterns

> This file is provided to the AI agent in every request to enforce best practices,
> performance, clean code principles, and the exact architectural patterns used in this project.
> **Always follow this file exactly. No deviations.**

---

## 🔁 Core Principle — Everything Must Be Dynamic & Reusable

> **This is a non-negotiable rule that applies to every single piece of code written in this project.**

- **No hardcoded values** — every value that can vary (IDs, strings, paths, config, roles, statuses, etc.) must come from config, constants, a request, a model, or a helper.
- **No one-off logic** — if something is written more than once, extract it into a shared helper, trait, scope, or service method.
- **Build for the entire project** — every class, method, helper, scope, trait, and component must be generic enough to be used anywhere in the project, not just the specific module or feature you are currently working on.
- **Shared logic belongs in `Modules/Common/`** — helpers, traits, base classes, and reusable utilities must live in the `Common` module so every other module can consume them.
- **Services are stateless and injectable** — design every Service so it can be injected and called from any controller or other service across all modules.
- **Scopes are reusable** — every Eloquent scope (`scopeActive`, `scopeFilter`, etc.) must be generic enough to be applied to any model that shares the same columns.
- **DTOs are schema-driven** — build DTOs to reflect the actual request shape; never embed values that are specific to a single use case.
- **Responses are uniform** — always use `returnMessage()` so every API response across every module has an identical structure.
- **Config over magic strings** — role names, folder paths, pagination sizes, and status values must be referenced from config or constants, never typed inline.

### ✅ Examples

```php
// ✅ Dynamic — role name from a constant / config
$user->hasRole(config('roles.super_admin'))

// ❌ Static — hardcoded magic string
$user->hasRole('Super Admin')

// ✅ Dynamic — folder path from config
$this->upload($file, config('uploads.branches_folder'))

// ❌ Static — hardcoded folder string
$this->upload($file, 'branches')

// ✅ Reusable scope on any model with `is_active`
public function scopeActive($query) { return $query->where('is_active', 1); }

// ❌ One-off inline filter in a controller
Model::where('is_active', 1)->get();
```

---

## 🛠 Stack

- **PHP**: 8.2.12
- **Framework**: Laravel 12
- **Architecture**: Modular (nWidart Laravel-Modules)
- **Auth**: Session-based (`Auth::attempt()`, `auth()->user()`, `auth()->logout()`)
- **Permissions**: Spatie Laravel-Permission
- **Image Processing**: Intervention Image
- **ORM**: Eloquent **only** — raw SQL is strictly forbidden

---

## ⚙️ File Generation — Always Use nWidart Artisan Commands

> **Never manually create module files using file creation tools.**
> Always use the appropriate `php artisan module:make-*` command to scaffold files,
> then modify the generated file to match project patterns.

### Module Scaffolding

```bash
# Create a full new module
php artisan module:make {ModuleName}

# Create individual module files
php artisan module:make-model {Name} {ModuleName}
php artisan module:make-controller {Name}Controller {ModuleName}
php artisan module:make-request {Name}Request {ModuleName}
php artisan module:make-migration create_{names}_table {ModuleName}
php artisan module:make-migration add_{column}_to_{table}_table {ModuleName}
php artisan module:make-seeder {Name}DatabaseSeeder {ModuleName}
php artisan module:make-factory {Name}Factory {ModuleName}
php artisan module:make-policy {Name}Policy {ModuleName}
php artisan module:make-provider {Name}ServiceProvider {ModuleName}
php artisan module:make-service {Name}Service {ModuleName}
```

### Rules
- **Always run the artisan command first**, then edit the generated file to match project patterns.
- Never use `create_file` or any file-creation tool to scaffold module boilerplate from scratch.
- After generation, update the file content to follow the DTO / Service / Policy / Controller patterns defined in this file.
- For migrations, always use `module:make-migration` with the correct naming convention (`create_*_table`, `add_*_to_*_table`).
- Register new modules in `modules_statuses.json` after creation.

---

## 📁 Module Structure

Every feature lives inside `Modules/{ModuleName}/` and follows this layout:

```
Modules/{ModuleName}/
├── Models/
│   └── {Name}.php
├── Http/
│   ├── Controllers/
│   │   └── api/
│   │       └── {Name}Controller.php
│   └── Requests/
│       └── {Name}Request.php
├── Policies/
│   └── {Name}Policy.php
├── Providers/
│   └── {Name}ServiceProvider.php
│   └── RouteServiceProvider.php
├── Routes/
│   └── api.php
│   └── web.php
├── DTO/
│   └── {Name}Dto.php
└── Service/
    └── {Name}Service.php
```

> **nWidart v2+ convention:** Models live in `Modules/{Module}/Models/` (not `Entities/`), matching the `app/Models` convention.

---

## 1. DTO (Data Transfer Object)

**Location:** `Modules/{Module}/DTO/{Name}Dto.php`

### Rules
- One DTO per entity.
- Constructor receives `$request` directly.
- All properties are `public`.
- Files/images: only assign if `$request->hasFile('field')` is true.
- Passwords: bcrypt inline inside the constructor.
- Boolean flags: use `isset($request['field']) ? 1 : 0`.
- `dataFromRequest()` converts DTO to array using `json_decode(json_encode($this), true)` then unsets null fields.
- Never include raw file objects in the returned array — file handling is done in the Service.
- **Translations:** store translatable fields as JSON array `['en' => ..., 'ar' => ...]` directly in the DTO.
- **Branch logic:**
  - If `auth()->user()->hasRole('Super Admin')`: use `branch_id` from the request.
  - If `Branch Manager`: always use `auth()->user()->branch_id` — ignore any `branch_id` in the request.

### Template

```php
<?php

namespace Modules\{Module}\DTO;

class {Name}Dto
{
    public $name;
    public $description;
    public $password;
    public $image;
    public $is_active;
    public $branch_id;

    public function __construct($request)
    {
        // Translatable fields stored as JSON
        $this->name        = ['en' => $request->get('name_en'), 'ar' => $request->get('name_ar')];
        $this->description = ['en' => $request->get('description_en'), 'ar' => $request->get('description_ar')];

        if ($request->get('password')) $this->password = bcrypt($request->get('password'));
        if ($request->hasFile('image')) $this->image   = $request->file('image');
        $this->is_active = isset($request['is_active']) ? 1 : 0;

        // Branch logic: Super Admin passes branch_id, Branch Manager uses own branch
        $user = auth()->user();
        $this->branch_id = $user->hasRole('Super Admin')
            ? $request->get('branch_id')
            : $user->branch_id;
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($data['password'] == null) unset($data['password']);
        if ($data['image'] == null) unset($data['image']);
        return $data;
    }
}
```

---

## 2. Service (Business Logic & DB Layer)

**Location:** `Modules/{Module}/Service/{Name}Service.php`

### Rules
- All database operations live here — never in the Controller.
- Use `use UploaderHelper;` trait for image/file uploads.
- Use Eloquent only: `Model::query()`, `with()`, `findOrFail()`, `create()`, `update()`, `delete()`, scopes, etc.
- Use `getCaseCollection($builder, $data)` for paginated or full results.
- Wrap multi-step writes in `DB::beginTransaction()` / `DB::commit()` / `DB::rollBack()`.
- Always `throw $e` inside `catch` so the Controller's try-catch can handle the response.
- Image upload: call `$this->upload($file, 'folder')` from `UploaderHelper`.
- Image delete: `File::delete(public_path('uploads/folder/' . $this->getImageName('folder', $model->image)))`.
- Toggle `is_active`: flip the boolean and call `->save()`.
- Use `findOrFail($id)` — never silent `find()` for single record lookups.
- Return the saved/updated model so the Controller can pass it to the response.

### Template

```php
<?php

namespace Modules\{Module}\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\{Module}\Entities\{Name};
use Modules\Common\Helper\UploaderHelper;

class {Name}Service
{
    use UploaderHelper;

    public function findAll(array $data = [], array $relations = [])
    {
        $query = {Name}::query()->with($relations)->latest();
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): {Name}
    {
        return {Name}::with($relations)->findOrFail($id);
    }

    public function findBy(string $key, $value, array $data = [], array $relations = [])
    {
        $query = {Name}::query()->with($relations)->where($key, $value)->latest();
        return getCaseCollection($query, $data);
    }

    public function active(array $relations = [])
    {
        return {Name}::active()->with($relations)->get();
    }

    public function save(array $data): {Name}
    {
        DB::beginTransaction();
        try {
            if (request()->hasFile('image')) {
                $data['image'] = $this->upload(request()->file('image'), '{folder}');
            }
            $record = {Name}::create($data);
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): {Name}
    {
        DB::beginTransaction();
        try {
            $record = $this->findById($id);
            if (request()->hasFile('image')) {
                File::delete(public_path('uploads/{folder}/' . $this->getImageName('{folder}', $record->image)));
                $data['image'] = $this->upload(request()->file('image'), '{folder}');
            }
            $record->update($data);
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function activate(int $id): void
    {
        $record = $this->findById($id);
        $record->is_active = !$record->is_active;
        $record->save();
    }

    public function delete(int $id): void
    {
        $record = $this->findById($id);
        File::delete(public_path('uploads/{folder}/' . $this->getImageName('{folder}', $record->image)));
        $record->delete();
    }
}
```

---

## 3. Form Request (Validation)

**Location:** `Modules/{Module}/Http/Requests/{Name}Request.php`

### Rules
- **One FormRequest class per controller** — handles store, update, delete, activate, and show actions all in one class.
- Use `$this->route('id')` and `$this->isMethod()` or `$this->routeIs()` to return different rules per action inside the same `rules()` method.
- Override `authorize()` to call the relevant **Policy** method — never return plain `true`.
- The `authorize()` method receives the model instance (for update/delete/show/activate) or just checks the user role (for store/index).
- For unique-with-ignore on update: use `Rule::unique('{table}', '{column}')->ignore($this->route('id'))`.
- For foreign key checks: `'field' => 'required|exists:{table},id'`.
- Controller type-hints the FormRequest — Laravel auto-validates and auto-authorizes before the method body runs.
- Custom messages go in the `messages()` method.
- **Branch logic in authorize():**
  - `Super Admin`: must pass `branch_id` in the request. The authorize method verifies it exists.
  - `Branch Manager`: `branch_id` is automatically pulled from `auth()->user()->branch_id` — no need to pass it.

### Template

```php
<?php

namespace Modules\{Module}\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\{Module}\Models\{Name};

class {Name}Request extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        $id   = $this->route('id');

        // Resolve model for actions that need it
        $model = $id ? {Name}::findOrFail($id) : null;

        return match (true) {
            $this->isMethod('POST') && !$id => $user->can('create', {Name}::class),
            $this->isMethod('PUT') || $this->isMethod('PATCH') => $user->can('update', $model),
            $this->isMethod('DELETE') => $user->can('delete', $model),
            $this->isMethod('GET') && $id => $user->can('view', $model),
            default => true,
        };
    }

    public function rules(): array
    {
        $id = $this->route('id');

        // UPDATE rules
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'name_en' => 'required|string|max:191',
                'name_ar' => 'required|string|max:191',
                'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'email'   => ['required', 'email', Rule::unique('{table}', 'email')->ignore($id)],
            ];
        }

        // STORE rules (POST)
        return [
            'name_en'   => 'required|string|max:191',
            'name_ar'   => 'required|string|max:191',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'email'     => 'required|email|unique:{table},email',
            'branch_id' => [
                Rule::requiredIf(fn() => auth()->user()?->hasRole('Super Admin')),
                'nullable',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => 'English name is required.',
            'name_ar.required' => 'Arabic name is required.',
        ];
    }
}
```

---

## 4. Policy & Gates

**Location:** `Modules/{Module}/Policies/{Name}Policy.php`

### Rules
- One Policy per Model.
- Register the policy in the module's `ServiceProvider` via `Gate::policy()`.
- Policy methods: `viewAny`, `view`, `create`, `update`, `delete`, `activate`.
- **Super Admin** can do anything — use `before()` hook to allow all actions.
- **Branch Manager** can only act on records belonging to their own branch — check `$model->branch_id === auth()->user()->branch_id`.
- Gates are defined in the Policy — never manually in routes or controllers.
- The `authorize()` method in FormRequest calls `$user->can('action', Model::class)` — Laravel resolves the Policy automatically.

### Template

```php
<?php

namespace Modules\{Module}\Policies;

use App\Models\User;
use Modules\{Module}\Models\{Name};
use Illuminate\Auth\Access\HandlesAuthorization;

class {Name}Policy
{
    use HandlesAuthorization;

    /**
     * Super Admin bypasses all policy checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Branch Manager');
    }

    public function view(User $user, {Name} $model): bool
    {
        return $user->branch_id === $model->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Branch Manager');
    }

    public function update(User $user, {Name} $model): bool
    {
        return $user->branch_id === $model->branch_id;
    }

    public function delete(User $user, {Name} $model): bool
    {
        return $user->branch_id === $model->branch_id;
    }

    public function activate(User $user, {Name} $model): bool
    {
        return $user->branch_id === $model->branch_id;
    }
}
```

### Register the Policy in ServiceProvider

```php
use Illuminate\Support\Facades\Gate;
use Modules\{Module}\Models\{Name};
use Modules\{Module}\Policies\{Name}Policy;

// Inside boot() of {Name}ServiceProvider:
Gate::policy({Name}::class, {Name}Policy::class);
```

---

## 5. API Controller

**Location:** `Modules/{Module}/Http/Controllers/api/{Name}Controller.php`

### Rules
- Extends `Illuminate\Routing\Controller`.
- Use `use UploaderHelper;` trait where needed.
- Inject the Service via constructor: `private {Name}Service $service`.
- Apply middleware in the constructor when needed: `$this->middleware('auth:guard')`.
- **All methods** must be wrapped in `try { ... } catch (\Exception $e) { return returnMessage(false, $e->getMessage(), null, 'server_error'); }`.
- Type-hint the **single** `{Name}Request` FormRequest in `store()`, `update()`, `destroy()`, `show()`, and `activate()` — Laravel auto-validates and auto-authorizes via Policy before the method body runs.
- Build DTO then call `->dataFromRequest()` to get the clean array passed to the Service.
- Use `returnMessage()` for ALL responses — never `response()->json()`.
- Auth is session-based: use `Auth::attempt()`, `auth()->user()`, `auth()->logout()`.
- **Branch logic in controller:** never resolve `branch_id` here — it is handled in the DTO. The controller just passes the DTO array to the service.
- Status string key mapping:
  - List / Show → `'ok'` (200)
  - Created → `'created'` (201)
  - Updated → `'accepted'` (202)
  - Deleted → `'ok'` (200)
  - Server error → `'server_error'` (500)

### Template

```php
<?php

namespace Modules\{Module}\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\{Module}\DTO\{Name}Dto;
use Modules\{Module}\Service\{Name}Service;
use Modules\{Module}\Http\Requests\{Name}Request;
use Modules\Common\Helper\UploaderHelper;

class {Name}Controller extends Controller
{
    use UploaderHelper;

    private {Name}Service $service;

    public function __construct({Name}Service $service)
    {
        $this->middleware('auth'); // adjust guard as needed
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $records = $this->service->findAll($request->all());
            return returnMessage(true, '{Name} List', $records);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store({Name}Request $request)
    {
        try {
            $data = (new {Name}Dto($request))->dataFromRequest();
            $record = $this->service->save($data);
            return returnMessage(true, '{Name} Created Successfully', $record, 'created');
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function show({Name}Request $request, int $id)
    {
        try {
            $record = $this->service->findById($id);
            return returnMessage(true, '{Name} Details', $record);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update({Name}Request $request, int $id)
    {
        try {
            $data = (new {Name}Dto($request))->dataFromRequest();
            $record = $this->service->update($id, $data);
            return returnMessage(true, '{Name} Updated Successfully', $record, 'accepted');
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy({Name}Request $request, int $id)
    {
        try {
            $this->service->delete($id);
            return returnMessage(true, '{Name} Deleted Successfully');
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function activate({Name}Request $request, int $id)
    {
        try {
            $this->service->activate($id);
            return returnMessage(true, '{Name} Status Updated', [], 'accepted');
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
```

### Auth Controller (Session-based)

```php
<?php

namespace Modules\{Module}\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return returnMessage(false, 'Invalid credentials', null, 'unauthorized');
            }
            $user = auth()->user();
            return returnMessage(true, 'Login Successfully', $user);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return returnMessage(true, 'Logged Out Successfully');
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function me()
    {
        try {
            return returnMessage(true, 'Profile', auth()->user());
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
```

---

## 5. Model (Eloquent Model)

**Location:** `Modules/{Module}/Models/{Name}.php`

### Rules
- Models live in `Modules/{Module}/Models/` — **not** `Entities/`. This follows nWidart v2+ convention.
- Extend `Illuminate\Database\Eloquent\Model`.
- Define `$fillable` explicitly — never use `$guarded = []`.
- Define `$hidden` for sensitive fields (passwords, tokens).
- Use `scopeActive($query)` for the standard `where('is_active', 1)` scope.
- Use `scopeFilter(Builder $query, array $data)` for reusable search/filter logic.
- Image accessor: return `asset('uploads/{folder}/' . $value)` when value is not null/empty.
- Date serialization: override `serializeDate()` to format as `'Y-m-d h:i A'`.
- All relationships defined as typed methods with return types.
- Use `HasFactory` trait.
- **Translations:** use `Spatie\Translatable\HasTranslations` trait and define `$translatable` array for any multilingual field. Translations are stored as JSON in the database column (single column, no extra tables). Always store as `['en' => '...', 'ar' => '...']`.

### Template

```php
<?php

namespace Modules\{Module}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class {Name} extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'description', 'image', 'is_active', 'branch_id'];

    // Translatable fields stored as JSON in DB
    public $translatable = ['name', 'description'];

    protected $hidden = [];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFilter(Builder $query, array $data): Builder
    {
        return $query
            ->when($data['name'] ?? null, fn($q) => $q->where('name->ar', 'LIKE', '%' . $data['name'] . '%'))
            ->when($data['branch_id'] ?? null, fn($q) => $q->where('branch_id', $data['branch_id']));
    }

    public function getImageAttribute($value): ?string
    {
        if ($value !== null && $value !== '') {
            return asset('uploads/{folder}/' . $value);
        }
        return $value;
    }

    // Relationships
    // public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
}
```

---

## 6. Routes

**Location:** `Modules/{Module}/Routes/api.php`

### Rules
- Group routes with `middleware`, `prefix`, and `namespace`.
- Use `Route::resource()` for standard CRUD.
- Add non-standard routes (`activate`, `search`, etc.) separately as `Route::get/post`.
- Auth routes (login/logout/refresh) are excluded from auth middleware via `except`.

### Template

```php
<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix'     => '{module}',
    'namespace'  => 'api',
], function ($router) {

    Route::post('login',  'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh','AuthController@refresh');
});

Route::group([
    'middleware' => ['api', 'auth:guard'],
    'namespace'  => 'api',
], function ($router) {

    Route::resource('{names}', '{Name}Controller');
    Route::get('{names}/activate/{id}', '{Name}Controller@activate');
});
```

---

## 7. Helper Functions (Global)

**Location:** `Modules/Common/Helper/ResponseHelper.php`

### `returnMessage()`

```php
returnMessage(bool $status, string $msg = null, $data = null, string $status_string = 'ok')
```

| `$status_string` | HTTP Code | When to use                  |
|------------------|-----------|------------------------------|
| `'ok'`           | 200       | Successful list/show/delete  |
| `'created'`      | 201       | Record created               |
| `'accepted'`     | 202       | Record updated / toggled     |
| `'validation_error'` | 422   | Validation failed            |
| `'server_error'` | 500       | Exception caught             |
| `'unauthorized'` | 401       | Unauthenticated              |
| `'forbidden'`    | 403       | Unauthorized action          |
| `'not_found'`    | 404       | Resource not found           |

### `getCaseCollection()`

```php
getCaseCollection(Builder $builder, array $data)
// If $data['paginated'] is set → paginate($data['paginated'])
// Otherwise → get()
```

---

## 8. UploaderHelper Trait

**Location:** `Modules/Common/Helper/UploaderHelper.php`

### Methods

```php
// Upload an image (with optional resize/minimize)
$this->upload($fileFromRequest, 'folder_name', $resize = false, $minimize = false): string

// Upload any file (non-image)
$this->uploadFile($fileFromRequest, 'folder_name'): string

// Extract filename from full path/URL
$this->getImageName('folder_name', $fullPathOrUrl): string
```

### Usage patterns

```php
// Save new image
if (request()->hasFile('image')) {
    $data['image'] = $this->upload(request()->file('image'), 'folder');
}

// Replace existing image on update
if (request()->hasFile('image')) {
    File::delete(public_path('uploads/folder/' . $this->getImageName('folder', $record->image)));
    $data['image'] = $this->upload(request()->file('image'), 'folder');
}

// Delete image on record delete
File::delete(public_path('uploads/folder/' . $this->getImageName('folder', $record->image)));
```

---

## 9. Coding Rules & Best Practices

### General
- PHP 8.2 features: typed properties, `match`, named arguments, `readonly`, nullsafe operator `?->`.
- All Service methods must have return types declared.
- No business logic in Controllers — Controllers only: receive request, validate, call service, return response.
- No raw SQL. Eloquent only: scopes, relationships, query builder methods.
- Always use `findOrFail()` for single lookups — never silent `find()`.
- Use `array_filter()` to strip null/empty values from data arrays before passing to create/update.
- Use `DB::beginTransaction()` / `commit()` / `rollBack()` for any operation touching 2+ tables.
- Always `throw $e` inside Service catch blocks so Controllers can handle it.

### Controllers
- Try-catch wraps **every** controller method body.
- Validation check runs **before** any service call.
- Never access `$request->all()` in the Service — pass processed array from DTO only.
- Use typed constructor injection for Services.
- **Route Model Binding:** When a controller method needs a single model instance (e.g. `show`, `edit`, `update`, `destroy`, `activate`), type-hint the model directly in the method signature instead of accepting `int $id` and calling `findById()`. Laravel resolves it automatically via route model binding.

```php
// ✅ Correct — Laravel injects the resolved model
public function activate(Branch $branch)
{
    $this->authorize('activate', $branch);
    $this->branchService->activate($branch->id);
}

// ❌ Forbidden — manual lookup is redundant
public function activate(int $id)
{
    $model = $this->branchService->findById($id);
    $this->authorize('activate', $model);
    $this->branchService->activate($id);
}
```

### DTOs
- Always use `json_decode(json_encode($this), true)` to convert DTO to array.
- Unset null fields explicitly after conversion.
- Never return file objects from `dataFromRequest()`.

### Services
- Use `UploaderHelper` trait via `use UploaderHelper;`.
- File upload/delete logic lives in the Service, not the Controller.
- Activate/toggle: always use `->save()`, not `->update()`.
- Use Eloquent relationships and eager loading (`with()`) — never lazy load inside loops.

### Entities
- Always define `$fillable`.
- Image accessors return full URL via `asset()`.
- Use local scopes (`scopeActive`, `scopeFilter`, etc.) for reusable query logic.
- Override `serializeDate()` for consistent date formatting.

### Validation & Authorization
- One `{Name}Request` FormRequest per controller — handles all actions (store, update, delete, show, activate).
- `authorize()` calls the Policy method — never returns plain `true`.
- `rules()` switches on `$this->isMethod()` to return store vs update rules.
- Use `Rule::unique('{table}', '{column}')->ignore($this->route('id'))` for update uniqueness.
- Use `exists:{table},id` for foreign key validation.

### Policies & Gates
- One Policy per Model registered via `Gate::policy()` in the module ServiceProvider.
- `before()` hook grants Super Admin unrestricted access.
- Branch Manager is restricted to records matching `$model->branch_id === auth()->user()->branch_id`.
- Never hard-code role checks in controllers or services — always delegate to Policy.

### Branch Logic
- Super Admin: must send `branch_id` in the request — resolved in the DTO.
- Branch Manager: `branch_id` is always `auth()->user()->branch_id` — resolved in the DTO automatically.
- Never resolve `branch_id` in the Controller or Service.

### Translations
- Use `Spatie\Translatable\HasTranslations` on every Model with multilingual fields.
- Define `public $translatable = ['name', 'description']` on the Model.
- Translations stored as JSON in a single DB column (e.g. `{"en": "...", "ar": "..."}`).
- In the DTO always build: `['en' => $request->get('name_en'), 'ar' => $request->get('name_ar')]`.
- Never create separate translation tables.

### Auth
- Session-based auth only: `Auth::attempt()`, `auth()->user()`, `auth()->logout()`.
- Invalidate session and regenerate CSRF token on logout.
- No JWT. No token refresh logic.

### Responses
- Always use `returnMessage()` global helper.
- Never use `response()->json()` in any controller.
- Consistent message format: `'{Name} Created Successfully'`, `'{Name} Updated Successfully'`, etc.

### Images & Files
- Folder: `public/uploads/{folder}/`
- Image stored as filename only in DB (not full URL).
- Full URL returned via Eloquent accessor on the model.
- Thumbnails (when needed): `$this->upload($file, 'folder', true)`.
- Minimized images: `$this->upload($file, 'folder', false, true)`.

---

## 10. Anti-Patterns — Never Do These

| ❌ Forbidden                                        | ✅ Correct                                                    |
|-----------------------------------------------------|--------------------------------------------------------------|
| Raw SQL (`DB::statement`, `DB::select`)             | Eloquent query builder / models                             |
| Business logic in Controller                        | Move to Service                                             |
| `response()->json()` in any controller              | `returnMessage()`                                           |
| `return_msg()` function                             | `returnMessage()` function                                  |
| Validation trait                                    | Dedicated `{Name}Request` FormRequest class                 |
| Multiple FormRequests per controller                | One `{Name}Request` handles all actions                     |
| `authorize()` returning `true`                      | `authorize()` delegates to Policy                           |
| Role checks in Controller or Service                | Always use Policy / Gates                                   |
| JWT auth / token-based auth                         | Session-based auth (`Auth::attempt()`)                      |
| Resolving `branch_id` in Controller or Service      | Resolve `branch_id` only in the DTO                         |
| Separate translation tables                         | Spatie Translatable with JSON column                        |
| Models in `Entities/` folder                        | Models in `Models/` folder (nWidart v2+)                    |
| `find($id)` without `OrFail`                        | `findOrFail($id)`                                           |
| Lazy loading relationships inside loops             | Eager load with `with()`                                    |
| Storing full image URL in DB                        | Store filename only, return URL via accessor                |
| `$guarded = []`                                     | Explicit `$fillable`                                        |
| Skipping try-catch in Controller                    | Wrap every method in try-catch                              |
| Skipping DB transaction for multi-writes            | Always use beginTransaction/commit/rollBack                 |
| Manual `->fails()` check in Controller              | Type-hint FormRequest — auto-validated & auto-authorized    |
| `findById($id)` in controller just to get a model   | Type-hint the Model in the method — use route model binding |

---

## 11. Quick Reference Checklist

When generating any new module or feature, verify:

- [ ] DTO created with `dataFromRequest()`, branch logic, and translation arrays
- [ ] Single `{Name}Request` FormRequest created — handles all actions, calls Policy in `authorize()`
- [ ] `{Name}Policy` created with `before()` for Super Admin and branch check for Branch Manager
- [ ] Policy registered via `Gate::policy()` in the module ServiceProvider
- [ ] Model in `Modules/{Module}/Models/` with `HasTranslations`, `$translatable`, `$fillable`, `scopeActive`, `scopeFilter`, image accessor, `serializeDate`
- [ ] Service created with `UploaderHelper` trait and all CRUD methods
- [ ] Controller uses `try-catch` on every method
- [ ] Controller type-hints `{Name}Request` in all methods — no manual `->fails()`
- [ ] Controller uses `returnMessage()` for all responses
- [ ] Auth is session-based — no JWT
- [ ] Routes use `Route::resource()` + explicit extra routes (activate, etc.)
- [ ] Image upload handled in Service via `UploaderHelper`
- [ ] DB transactions used for multi-table writes
- [ ] Translatable fields stored as JSON (`{"en":"...","ar":"..."}`) in DB — no separate translation tables
- [ ] No raw SQL anywhere
- [ ] No business logic in Controller
- [ ] No role/branch checks in Controller or Service — only in Policy and DTO
- [ ] Everything is dynamic and reusable — no hardcoded values, no one-off logic, shared utilities live in `Modules/Common/`
