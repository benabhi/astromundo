@props(['crumbs'])

<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs font-mono text-slate-400 uppercase tracking-widest">
    @foreach($crumbs as $crumb)
        @if(!$loop->first)
            <span class="text-slate-600">/</span>
        @endif
        
        @if(isset($crumb['url']))
            <a href="{{ $crumb['url'] }}" class="text-slate-500 hover:text-blue-400 transition-colors">
                {{ $crumb['label'] }}
            </a>
        @else
            <span class="text-white font-bold">{{ $crumb['label'] }}</span>
        @endif

        @if(isset($crumb['extra']))
            <span class="text-slate-600">({{ $crumb['extra'] }})</span>
        @endif
    @endforeach
</nav>
