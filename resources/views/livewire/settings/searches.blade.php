<div>
    <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
    <div class="header-search">
        <form wire:submit.prevent="search">
            <div class="form-group mb-0 position-relative" id="search-form">
                <i class="dw dw-search2 search-icon"></i>
                <input type="text" class="form-control search-input" placeholder="Tìm kiếm..." id="search"
                    wire:model="keyword" wire:keydown="getSuggestions" autocomplete="off"/>
                <!-- Gợi ý -->

                @if (!empty($suggestions) && strlen($keyword) > 0)
                    <ul class="list-group position-absolute w-100" style="top: 100%; z-index: 1000;">
                        @foreach ($suggestions as $suggestion)
                            <li class="list-group-item list-group-item-action"
                                wire:click="selectSuggestion('{{ $suggestion['route'] }}')">
                                {{ $suggestion['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </form>
    </div>
</div>
