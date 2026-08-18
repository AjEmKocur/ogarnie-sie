<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.services', [
            'categories' => ServiceCategory::withCount([
                'services',
                'services as active_services_count' => fn ($query) => $query->where('is_active', true),
            ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'uncategorizedServicesCount' => Service::whereNull('service_category_id')->count(),
            'uncategorizedActiveServicesCount' => Service::whereNull('service_category_id')->where('is_active', true)->count(),
        ]);
    }

    public function showCategory(ServiceCategory $serviceCategory): View
    {
        return view('admin.cms.service-category', [
            'category' => $serviceCategory->load(['services' => fn ($query) => $query
                ->orderBy('sort_order')
                ->orderBy('name')]),
            'categories' => ServiceCategory::orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function uncategorized(): View
    {
        return view('admin.cms.service-category', [
            'category' => null,
            'categories' => ServiceCategory::orderBy('sort_order')->orderBy('name')->get(),
            'services' => Service::with('category')
                ->whereNull('service_category_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:service_categories,name'],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ServiceCategory::create([
            ...$validated,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.cms.services.categories.show', $category)
            ->with('status', __('Kategoria dodana.'));
    }

    public function updateCategory(Request $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:service_categories,name,'.$serviceCategory->id],
            'description' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $serviceCategory->update([
            ...$validated,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.cms.services.categories.show', $serviceCategory)
            ->with('status', __('Kategoria zaktualizowana.'));
    }

    public function destroyCategory(ServiceCategory $serviceCategory): RedirectResponse
    {
        if ($serviceCategory->services()->exists()) {
            return redirect()
                ->route('admin.cms.services.categories.show', $serviceCategory)
                ->with('status', __('Najpierw przenieś albo usuń usługi z tej kategorii.'));
        }

        $serviceCategory->delete();

        return redirect()
            ->route('admin.cms.services.index')
            ->with('status', __('Kategoria usunięta.'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'long_description' => ['nullable', 'string', 'max:20000'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Service::create([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return $this->redirectAfterServiceChange($validated['service_category_id'] ?? null, __('Usługa dodana.'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'long_description' => ['nullable', 'string', 'max:20000'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $service->update([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return $this->redirectAfterServiceChange($validated['service_category_id'] ?? null, __('Usługa zaktualizowana.'));
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'services' => ['required', 'array', 'min:1'],
            'services.*.service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'services.*.name' => ['required', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string', 'max:5000'],
            'services.*.long_description' => ['nullable', 'string', 'max:20000'],
            'services.*.price_from' => ['nullable', 'numeric', 'min:0'],
            'services.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'services.*.is_active' => ['nullable', 'boolean'],
        ]);

        $ids = array_keys($validated['services']);
        $services = Service::whereIn('id', $ids)->get()->keyBy('id');

        foreach ($validated['services'] as $serviceId => $data) {
            $service = $services->get((int) $serviceId);

            if (! $service) {
                continue;
            }

            $service->update([
                'service_category_id' => $data['service_category_id'] ?? null,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'long_description' => $data['long_description'] ?? null,
                'price_from' => $data['price_from'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => (bool) ($data['is_active'] ?? false),
            ]);
        }

        return redirect()
            ->back()
            ->with('status', __('Wszystkie zmiany w usługach zostały zapisane.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->back()
            ->with('status', __('Usługa usunięta.'));
    }

    private function redirectAfterServiceChange(int|string|null $categoryId, string $status): RedirectResponse
    {
        if ($categoryId) {
            return redirect()
                ->route('admin.cms.services.categories.show', $categoryId)
                ->with('status', $status);
        }

        return redirect()
            ->route('admin.cms.services.uncategorized')
            ->with('status', $status);
    }
}
