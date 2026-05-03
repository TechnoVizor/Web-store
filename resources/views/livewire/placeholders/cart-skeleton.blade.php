<main class="container mx-auto px-6 py-16 max-w-6xl">
    <style>
        .skeleton-shimmer {
            background: linear-gradient(90deg, #050505 25%, #111111 50%, #050505 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    </style>

    <div class="flex items-center space-x-4 mb-12">
        <div class="w-1 h-10 bg-white/10"></div>
        <div class="space-y-2">
            <div class="h-8 w-64 skeleton-shimmer"></div>
            <div class="h-2 w-32 skeleton-shimmer opacity-20"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-8">
            @for($i = 0; $i < 3; $i++)
                <div class="flex items-start space-x-6 border-b border-white/5 pb-8">
                    <div class="w-24 h-32 skeleton-shimmer shrink-0 border border-white/5"></div>
                    <div class="flex-grow space-y-4">
                        <div class="h-4 w-1/2 skeleton-shimmer"></div>
                        <div class="h-3 w-1/4 skeleton-shimmer opacity-30"></div>
                        <div class="h-8 w-32 skeleton-shimmer pt-4"></div>
                    </div>
                </div>
            @endfor
        </div>
        <div class="lg:col-span-1">
            <div class="border border-white/10 p-8 h-64 skeleton-shimmer opacity-50"></div>
        </div>
    </div>
</main>