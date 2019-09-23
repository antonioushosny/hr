@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
         
         {{trans('admin.Welcome to fannie')}}
        @endcomponent
    @endslot
{{-- Body --}}
   <h3>
 
    </h3> <br>
    <h4>كود  تاكيد بريدك الالكتروني هو :- </h4> <br> <h3>{{$code}}</h3> 

    
{{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} Fannie 4U.!
        @endcomponent
    @endslot
@endcomponent