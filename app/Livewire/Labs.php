<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Labs extends Component
{
    public $projects = [
        [
            'id' => 'RD-01',
            'title' => 'City_Layers',
            'status' => 'Live',
            'year' => '2025',
            'materials' => 'Cotton / Nylon / Softshell',
            'description' => 'A clean citywear direction built around lightweight jackets, relaxed tees, and easy layering for changing weather.',
            'specs' => [
                'Fit' => 'Relaxed',
                'Season' => 'All year',
                'Palette' => 'Grey / Black',
                'Care' => 'Easy',
            ],
            'log' => [
                '2025.02.14' => 'Core layering pieces selected.',
                '2025.04.10' => 'Fabric hand-feel and wash tests approved.',
                '2025.06.01' => 'Collection prepared for storefront.',
            ],
        ],
        [
            'id' => 'RD-02',
            'title' => 'Soft_Tailoring',
            'status' => 'Preview',
            'year' => '2026',
            'materials' => 'Wool Blend / Viscose / Stretch Fiber',
            'description' => 'Structured pieces with softer movement: designed for workdays, evenings, and travel without losing shape.',
            'specs' => [
                'Fit' => 'Tailored',
                'Season' => 'Spring',
                'Palette' => 'Black / Bone',
                'Care' => 'Gentle',
            ],
            'log' => [
                '2026.01.05' => 'First silhouette review completed.',
                '2026.02.18' => 'Sample adjustments sent to production.',
                '2026.03.22' => 'Editorial styling test started.',
            ],
        ],
        [
            'id' => 'RD-03',
            'title' => 'Weekend_Essentials',
            'status' => 'Coming',
            'year' => '2026',
            'materials' => 'Heavy Cotton / Jersey / Rib Knit',
            'description' => 'Comfort-first essentials with a sharper cut: tees, hoodies, and daily pieces made to rotate often.',
            'specs' => [
                'Fit' => 'Regular',
                'Season' => 'Weekend',
                'Palette' => 'Washed neutrals',
                'Care' => 'Machine wash',
            ],
            'log' => [
                '2026.02.01' => 'Fabric weight confirmed.',
                '2026.03.10' => 'Fit grading completed.',
                '2026.04.15' => 'Launch styling in progress.',
            ],
        ],
    ];

    public $activeProject;

    public function mount()
    {
        $this->activeProject = $this->projects[0];
    }

    public function selectProject($id)
    {
        $this->activeProject = collect($this->projects)->firstWhere('id', $id);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.labs');
    }
}
