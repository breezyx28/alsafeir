<?php

namespace App\Http\Livewire;

use App\Models\Measurement;
use Livewire\Component;

class ProductionBoard extends Component
{
    public $pending;
    public $inProgress;
    public $ready;

    public $search = '';
    public ?Measurement $selectedMeasurement = null;

    public function mount()
    {
        $this->loadMeasurements();
    }

    public function loadMeasurements()
    {
        $query = Measurement::with(['order.customer', 'order.products.variant.product'])
            ->whereHas('order', function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function ($cq) {
                      $cq->where('name', 'like', '%' . $this->search . '%');
                  });
            });

        $this->pending = (clone $query)->where('status', 'قيد الانتظار')->latest()->get();
        $this->inProgress = (clone $query)->where('status', 'تحت التنفيذ')->latest()->get();
        $this->ready = (clone $query)->where('status', 'جاهزة في المشغل')->latest()->get();
    }

    public function updatedSearch()
    {
        $this->loadMeasurements();
    }

    // الدالة أصبحت أبسط ويتم استدعاؤها مباشرة
    public function updateStatus($measurementId, $newStatus)
    {
        $measurement = Measurement::find($measurementId);
        if ($measurement) {
            $measurement->status = $newStatus;
            $measurement->save();
        }
        $this->loadMeasurements();
    }

    public function showDetails($measurementId)
    {
        $this->selectedMeasurement = Measurement::with(['order.customer', 'order.products.variant'])->find($measurementId);
    }

    public function render()
    {
        return view('livewire.production-board');
    }
}
