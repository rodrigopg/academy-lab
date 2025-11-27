<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Cabeçalho com Filtros e Busca --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col lg:flex-row gap-4">
                {{-- Busca --}}
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Buscar produtos
                    </label>
                    <input 
                        type="text" 
                        id="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Digite o nome do produto..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    />
                </div>

                {{-- Filtro de Status --}}
                <div class="lg:w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select 
                        id="status"
                        wire:model.live="statusFilter"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                        <option value="all">Todos</option>
                        <option value="active">Ativos</option>
                        <option value="suspended">Suspensos</option>
                        <option value="canceled">Cancelados</option>
                    </select>
                </div>

                {{-- Ordenação --}}
                <div class="lg:w-48">
                    <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ordenar por
                    </label>
                    <select 
                        id="sort"
                        wire:model.live="sortBy"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >
                        <option value="name">Nome (A-Z)</option>
                        <option value="recent">Mais Recentes</option>
                        <option value="progress">Maior Progresso</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Lista de Produtos --}}
        <div class="space-y-6">
            @forelse($this->products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            {{-- Imagem do Produto --}}
                            <div class="lg:w-48 shrink-0">
                                @if($product->cover)
                                    <img 
                                        src="{{ \Illuminate\Support\Facades\Storage::temporaryUrl($product->cover, now()->addMinute()) }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-32 object-cover rounded-lg"
                                    />
                                @else
                                    <div class="w-full h-32 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-white/50">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Conteúdo do Produto --}}
                            <div class="flex-1 space-y-4">
                                {{-- Título e Status --}}
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="mt-2 text-gray-600 dark:text-gray-400 line-clamp-2">
                                            {!! $product->description !!}
                                        </p>
                                    </div>
                                    
                                    {{-- Badge de Status --}}
                                    <x-filament::badge 
                                        :color="$this->getStatusBadgeColor($product->user_status)"
                                        class="shrink-0"
                                    >
                                        {{ $this->getStatusLabel($product->user_status) }}
                                    </x-filament::badge>
                                </div>

                                {{-- Barra de Progresso --}}
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Progresso
                                        </span>
                                        <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                            {{ $product->stats['progress_percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div 
                                            class="bg-primary-600 dark:bg-primary-500 h-3 rounded-full transition-all duration-500"
                                            style="width: {{ $product->stats['progress_percentage'] }}%"
                                        ></div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $product->stats['completed_lessons'] }} de {{ $product->stats['total_lessons'] }} aulas concluídas
                                        • 
                                        {{ $this->formatDuration($product->stats['watched_duration']) }} assistido
                                    </p>
                                </div>

                                {{-- Estatísticas --}}
                                <div class="flex flex-wrap gap-6">
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                        <span class="text-sm font-medium">{{ $product->stats['total_courses'] }} cursos</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium">{{ $this->formatDuration($product->stats['total_duration']) }} total</span>
                                    </div>

                                    @if($product->expires_at)
                                        @php
                                            $daysRemaining = $this->getDaysRemaining($product->expires_at);
                                        @endphp
                                        <div class="flex items-center gap-2 {{ $daysRemaining <= 30 ? 'text-warning-600' : 'text-gray-600 dark:text-gray-400' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            <span class="text-sm font-medium">
                                                @if($daysRemaining > 0)
                                                    Expira em {{ $daysRemaining }} {{ $daysRemaining === 1 ? 'dia' : 'dias' }}
                                                @else
                                                    Expirado
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Ações --}}
                                <div class="flex flex-wrap gap-3 pt-2">
                                    <x-filament::button
                                        :href="route('filament.member.pages.produto.{product}', $product->id)"
                                        tag="a"
                                        color="primary"
                                        size="lg"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                        </svg>
                                        Acessar Conteúdo
                                    </x-filament::button>

                                    @if($product->stats['progress_percentage'] >= 100)
                                        <x-filament::button
                                            color="success"
                                            outlined
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                                            </svg>
                                            Ver Certificado
                                        </x-filament::button>
                                    @endif

                                    @if($product->redirect_url)
                                        <x-filament::button
                                            :href="$product->redirect_url"
                                            target="_blank"
                                            color="gray"
                                            outlined
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                            </svg>
                                            Suporte
                                        </x-filament::button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Estado Vazio --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Nenhum produto encontrado
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @if($search)
                                Não encontramos produtos com o termo "{{ $search }}".
                            @elseif($statusFilter !== 'all')
                                Você não possui produtos com o status selecionado.
                            @else
                                Você ainda não possui produtos ativos.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
