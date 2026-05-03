<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Labs extends Component
{
    public $projects = [
        [
            'id' => 'RD-01',
            'title' => 'Neural_Interface',
            'status' => 'Stable',
            'year' => '2025',
            'materials' => 'Carbon Fiber / Graphene / Bio-Polymer',
            'description' => 'The first iteration of our neural feedback system. Designed for seamless integration between digital thought and physical execution. Minimizing latency to 0.04ms.',
            'specs' => [
                'Bandwidth' => '4.2 Tbps',
                'Response_Time' => '0.04ms',
                'Power_Consumption' => '12W',
                'Sync_Rate' => '99.9%'
            ],
            'log' => [
                '2025.02.14' => 'Initial neural handshake successful.',
                '2025.04.10' => 'Material integrity confirmed under high thermal load.',
                '2025.06.01' => 'Transitioning to production-ready firmware.'
            ]
        ],
        [
            'id' => 'RD-02',
            'title' => 'Carbon_Shell',
            'status' => 'Testing',
            'year' => '2026',
            'materials' => 'Monolithic T800 Carbon / Titanium Grade 5',
            'description' => 'Research into ultra-lightweight exoskeleton structures. Focus on structural rigidity and impact absorption for high-stakes urban environments.',
            'specs' => [
                'Tensile_Strength' => '4900 MPa',
                'Density' => '1.76 g/cm³',
                'Elastic_Modulus' => '230 GPa',
                'Weight_Reduction' => '42%'
            ],
            'log' => [
                '2026.01.05' => 'Stress test 01: 400% load capacity exceeded.',
                '2026.02.18' => 'Titanium reinforcement points optimized.',
                '2026.03.22' => 'Field testing initiated in Zone_04.'
            ]
        ],
        [
            'id' => 'RD-03',
            'title' => 'Optical_Sensor',
            'status' => 'Experimental',
            'year' => '2026',
            'materials' => 'Synthetic Sapphire / CMOS Architecture',
            'description' => 'A multi-spectral sensing array capable of real-time environmental mapping. Developed for autonomous navigation and enhanced spatial awareness.',
            'specs' => [
                'Resolution' => '124 MP',
                'Spectrum' => '400nm - 1100nm',
                'FOV' => '210°',
                'Processing' => 'Dedicated AI Core'
            ],
            'log' => [
                '2026.02.01' => 'Spectrum sensitivity expanded to infrared.',
                '2026.03.10' => 'Miniaturization process successful.',
                '2026.04.15' => 'Testing low-light performance in subterranean conditions.'
            ]
        ]
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