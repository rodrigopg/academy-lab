<aside class="lg:col-span-5">
    <div class="lg:sticky lg:top-24">
        <div class="rounded-3xl bg-neutral-900/70 ring-1 ring-white/10 shadow-2xl overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="mb-6 flex items-start gap-4">
                    <!-- Placeholder ícone grande -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                    </svg>

                    <div>
                        <p class="text-sm text-neutral-400">{{ $track->track->name }}</p>
                        <h2 class="text-2xl font-bold">{{ $track->track->name }}</h2>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-sm text-neutral-300">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                        </svg>
                        <span>{{ $track->productTrackCourses->count() }} Cursos</span>
                    </div>
                    <span class="h-3 w-px bg-neutral-700"></span>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                        <span class="text-xs">{{ \Carbon\CarbonInterval::seconds($track->productTrackCourses->sum('course.duration'))->cascade()->forHumans() }}</span>
                    </div>
                </div>

                <p class="mt-5 text-neutral-300">
                    {!! $track->track->description !!}
                </p>

                <div class="mt-8 flex gap-3">
                    <a href="#start"
                       class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2 font-semibold text-white-900 hover:bg-primary-darker transition">
                        Iniciar caminho
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>
