<x-filament-panels::page>
    <div
        class="min-h-screen"
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('panda-player') }}"
        x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('panda-api'))]"
        x-data="pandaPlayer({
            elementId: 'panda-video',
            activeLesson: {{ $activelesson }},
            options: { }
        })"
        x-on:destroy.window="destroy()"
        wire:key="livewire-comp-{{ $activelesson->id }}"
    >
        <!-- Sidebar -->
        <aside id="sidebar"
               class="fixed top-0 left-0 z-50 h-full w-70 border-r border-neutral-1000 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center space-x-3">
                    <a
                        href="{{route('filament.member.pages.produto.{product}', $product->id)}}"
                        wire:navigate
                        class="bg-neutral-1000 rounded-full flex items-center justify-center p-2">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>

                    </a>
                    <img src="{{ asset('logos/logo-white.png') }}" class="h-10">
                </div>
                <button id="closeSidebar"
                        class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors lg:hidden">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Course Info -->
            <div class="p-3 flex flex-col gap-y-6" x-data="{ openModules: {} }"
                x-init="openModules[{{ $activelesson->module_id }}] = !openModules[{{ $activelesson->module_id }}]"
            >
                @foreach($course->modules as $module)
                    <div class="flex flex-col rounded-lg  bg-neutral-1000/30">
                        <!-- Cabeçalho do módulo -->
                        <button
                            @click="openModules[{{ $module->id }}] = !openModules[{{ $module->id }}]"
                            class="flex justify-between items-center w-full p-4 text-left"
                        >
                            <div class="flex flex-row items-center justify-between w-full gap-x-4">
                                <h3 class="font-semibold text-white text-sm">
                                    {{ $module->name }}
                                </h3>
                                <span class="text-sm text-gray-400">({{ $module->lessons->whereNotNull('userLessonStatus.completed_at')->count() }}/{{ $module->lessons->count() }})</span>
                            </div>
                            <svg
                                :class="{'rotate-90': openModules[{{ $module->id }}]}"
                                class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <!-- Conteúdo colapsável -->
                        <div
                            x-show="openModules[{{ $module->id }}]"
                            x-collapse
                            class="flex flex-col gap-2 p-2 pt-0"
                        >
                            @foreach($module->lessons as $lesson)
                                <button
                                    @click="changeLesson(@js($lesson))"
                                    class="w-full group text-start flex items-center gap-3 p-2 rounded-xl border transition-all duration-200 hover:border-primary hover:bg-primary/5 cursor-pointer border-border {{ $activelesson?->id == $lesson->id ? 'border-primary bg-primary/5' : 'bg-neutral-1000/30' }}"
                                >
                                    <div
                                        @class([
                                            'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 text-secondary-foreground group-hover:bg-primary group-hover:text-primary-foreground',
                                            'bg-primary' => $activelesson?->id == $lesson->id,
                                            'bg-[#2ECC71]' => $lesson?->userLessonStatus?->completed_at,
                                            'bg-neutral-1200' => !$lesson?->userLessonStatus?->completed_at
                                        ])
                                    >
                                        @if($lesson->userLessonStatus && $lesson->userLessonStatus->completed_at)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="text-white w-5 h-5">
                                                <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                            </svg>
                                        @endif

                                    </div>
                                    <span
                                        class="flex-1 text-sm text-white transition-colors text-muted-foreground group-hover:text-foreground">
                                        {{ $lesson->name }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <!-- Main Content -->
        <div id="mainContent" class="transition-all duration-300 lg:ml-70">
            <!-- Header -->
            <header class="border-b border-neutral-1000 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h1 id="currentLessonTitle" class="text-lg font-semibold text-white-900">
                        {{ $course->name }}
                    </h1>
                </div>
            </header>

            <!-- Content Grid -->
            <div class="p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Column - Video and Description -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Video Player -->
                    <div class="bg-white rounded-lg shadow-sm border border-neutral-1000 overflow-hidden">
                        <div  class="relative bg-black aspect-video">
                            <iframe id="panda-video" class="w-full h-full object-contain"
                                    src="{{ $activelesson?->panda_player_url }}">
                            </iframe>
                        </div>
                    </div>

                    <!-- Lesson Description -->
                    <div class="bg-neutral-1000/30 rounded-lg shadow-sm">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">

                                    <div>
                                        <h3 class="text-lg font-semibold text-white-900">Sobre esta aula</h3>
                                        <p class="text-sm text-neutral-400">Descrição e objetivos</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div id="descriptionContent" class="space-y-4">
                                <div>
                                    <h4 id="lessonTitle"
                                        class="font-medium text-white-900 mb-2">{{ $activelesson->name }}</h4>
                                    <div
                                        x-data="{
                                            open: false,
                                            collapsed: 220, // altura em px quando fechado (ajuste se quiser)
                                            needsToggle: false,
                                            checkToggle() {
                                              // mostra o botão só se houver overflow
                                              this.needsToggle = this.$refs.desc.scrollHeight > this.$refs.desc.clientHeight + 4
                                            }
                                          }"
                                        x-init="$nextTick(() => { checkToggle(); window.addEventListener('resize', () => checkToggle()) })"
                                        class="relative"
                                    >
                                        <div
                                            id="lessonDescription"
                                            x-ref="desc"
                                            class="richtext leading-relaxed overflow-hidden transition-all duration-300"
                                            :style="open ? '' : `max-height:${collapsed}px`"
                                        >
                                            {!! $activelesson->description !!}
                                        </div>

                                        <!-- Fade no rodapé quando fechado -->
                                        <div
                                            x-show="!open"
                                            x-transition.opacity
                                            class="pointer-events-none absolute inset-x-0 bottom-0 h-16 "
                                        ></div>

                                        <!-- Botão Mostrar mais/menos (só aparece se precisar) -->
                                        <div x-show="needsToggle" class="mt-2">
                                            <button
                                                @click="open = !open"
                                                class="text-sm text-primary inline-flex items-center gap-1 hover:underline"
                                            >
                                                <span x-text="open ? 'Mostrar menos' : 'Mostrar mais'"></span>
                                                <svg class="size-4 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <!-- Materials -->
                    @if ($activelesson->materials->count())
                    <div class="bg-neutral-1000/30 rounded-lg shadow-sm ">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center space-x-3 mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-white-900">Material Complementar</h3>
                                    <p class="text-sm text-gray-500"><span
                                            id="materialsCount">{{ $activelesson->materials->count() }}</span> arquivos
                                        disponíveis</p>
                                </div>
                            </div>


                            <!-- Quick Actions -->
                            <div class="mt-4 pt-4 ">
                                @foreach($activelesson->materials as $material)
                                    <button
                                        wire:click="download({{$material->id}})"
                                        class="w-full group text-start flex items-center gap-3 p-2 rounded-xl transition-all duration-200 hover:border-primary hover:bg-primary/5 cursor-pointer bg-neutral-800/30"
                                    >
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ $activelesson?->id == $lesson->id ? 'bg-primary' : 'bg-neutral-1200' }} text-secondary-foreground group-hover:bg-primary group-hover:text-primary-foreground"
                                        >
                                            <!-- SVG do arquivo (visível quando NÃO está carregando) -->
                                            <svg
                                                wire:loading.remove
                                                wire:target="download({{$material->id}})"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-6"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                            </svg>

                                            <!-- Spinner de loading (visível quando está carregando) -->
                                            <svg
                                                wire:loading
                                                wire:target="download({{$material->id}})"
                                                class="animate-spin size-6"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                        stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>

                                        <span
                                            class="flex-1 text-sm text-white transition-colors text-muted-foreground group-hover:text-foreground">
                                            {{ $material->title }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Chat AI -->
                    <livewire:chat-agent
                        wire:key="{{ \Illuminate\Support\Str::uuid() }}"
                        :$activelesson
                        :agent_url="config('n8n.lesson_agent_endpoint')"
                    />

                    <div class="bg-neutral-1000/30 rounded-lg shadow-sm ">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-center space-x-3">
                                <button
                                    wire:click="completeLesson(true)"
                                    class="w-full group text-start flex items-center gap-3 p-2 rounded-xl transition-all duration-200 hover:border-primary hover:bg-primary/5 cursor-pointer bg-neutral-800/30"
                                >
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ $activelesson?->id == $lesson->id ? 'bg-primary' : 'bg-neutral-1200' }} text-secondary-foreground group-hover:bg-primary group-hover:text-primary-foreground"
                                    >
                                        <!-- SVG do arquivo (visível quando NÃO está carregando) -->
                                        @if($activelesson->userLessonStatus && $activelesson->userLessonStatus->completed_at)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        @else
                                            <svg
                                                wire:loading.remove
                                                wire:target="completeLesson(true)"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd" />
                                            </svg>
                                        @endif

                                        <!-- Spinner de loading (visível quando está carregando) -->
                                        <svg
                                            wire:loading
                                            wire:target="completeLesson(true)"
                                            class="animate-spin size-6"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <span
                                        class="flex-1 text-sm text-white transition-colors text-muted-foreground group-hover:text-foreground">
                                        @if($activelesson->userLessonStatus && $activelesson->userLessonStatus->completed_at)
                                            <p>Aula concluida</p>
                                            <p class="text-xs">Completada em: {{ $activelesson->userLessonStatus->completed_at->format('d/m/Y') }}</p>
                                        @else
                                            Completar Aula
                                        @endif
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="px-6 pb-6">
                <div class="bg-neutral-1000/30 rounded-lg shadow-sm">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="message-circle" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white-900">Comentários</h3>
                                <p class="text-sm text-white-500"><span id="commentsCount">{{ $activelesson->comments->count() }}</span> comentários</p>
                            </div>
                        </div>

                        <!-- New Comment Input -->
                        <div class="mb-6">
                            <div class="flex space-x-3">
                                <div class="flex-1">
                                    <textarea id="newComment"
                                              placeholder="Compartilhe suas dúvidas ou comentários sobre a aula..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              rows="3"></textarea>
                                    <div class="flex justify-end mt-3">
                                        <button id="submitComment"
                                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                            Comentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comments List -->
                        <div class="space-y-6" id="commentsList">
                            <!-- Comments will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <x-next-class-modal />
    </div>

</x-filament-panels::page>
