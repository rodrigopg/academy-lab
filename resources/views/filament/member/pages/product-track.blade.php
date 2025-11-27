<x-filament-panels::page>
    <header class="pt-10 relative">
        <section class="mx-auto max-w-6xl px-4 text-center mt-6 z-10 relative">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">{{ $product->name }}</h1>
            <p class="mt-3 text-primary">
                {!! $product->description !!}
            </p>
        </section>
        <div class="px-4 h-[300px] overflow-hidden absolute w-full top-0 z-1 mask-radial-top">
            @if($product->cover)
                <img alt="Capa"
                     src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl($product->cover, now()->addMinute()) }}"
                     class="w-full object-cover"/>
            @else
                <div
                    class="w-full h-full bg-gradient-to-br from-primary to-primary-darker flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-32 h-32 text-white/30">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
            @endif
        </div>
    </header>

    <!-- hero -->


    <main class="mx-auto max-w-6xl px-4 mt-32 pb-24">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            @foreach ($product->productTracks as $track)
                <!-- Card da esquerda -->
{{--                <x-track-card :track="$track"/>--}}

                <!-- Timeline da direita -->
                <section class="lg:col-span-7">
                    <ol class="relative border-s border-primary/80 pl-6">
                        <!-- item -->

                        @foreach($track->trackCourses as $tc)
                            @foreach($tc->courses as $course)
                                <li class="mb-10 ms-4">
                                    <span
                                        class="absolute -start-2.5 mt-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary ring-2 ring-primary/60"></span>
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="h-14 w-14 shrink-0 rounded-full bg-primary/70 ring-1 ring-white/10 overflow-hidden">
                                            @if($course->course->cover)
                                                <img
                                                    src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl($course->course->cover, now()->addMinute()) }}"
                                                    class="h-full w-full object-cover"
                                                    alt="{{ $course->course->name }}">
                                            @else
                                                <div
                                                    class="h-full w-full bg-gradient-to-br from-primary to-primary-darker flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                         class="w-6 h-6 text-white/50">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('filament.member.pages.produto.{product}.class-room.{track}.{course}.{slug}', [
                                            'product' => $product->id,
                                            'track' => $track->track_id,
                                            'course' => $course->course->id,
                                            'slug' => $course->course->slug
                            ]) }}" class="text-lg font-semibold hover:underline">{{ $course->course->name }}</a>
                                            <p class="text-sm text-primary">{{ $course->course->description }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endforeach
                    </ol>
                </section>
            @endforeach
        </div>
    </main>
</x-filament-panels::page>
