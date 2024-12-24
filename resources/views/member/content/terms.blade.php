@extends('member.layouts.default', ['robots' => false])

@section('page_title', trans('common.terms') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<section class="w-full max-w-7xl mx-auto lg:h-full">
    <div class="p-10 max-w-4xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
        {!! $content !!}
    </div>
</section>
@stop
