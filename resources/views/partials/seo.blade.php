@php
    if(isset($seoContents) && count($seoContents)){
        $seoContents        = json_decode(json_encode($seoContents, true));
        $socialImageSize    = explode('x', $seoContents->image_size);
    }elseif($seo){
        $seoContents        = $seo;
        $socialImageSize    = explode('x', getFileSize('seo'));
        $seoContents->image = getImage(getFilePath('seo').'/'. $seo->image);
    }else{
        $seoContents = null;
    }
@endphp

<meta name="title" Content="{{ $general->sitename(__($pageTitle)) }}">

@if($seoContents)
    <meta name="description" content="{{ $seoContents->meta_description??$seoContents->description }}">
    <meta name="keywords" content="{{ implode(',',$seoContents->keywords) }}">
    <link rel="shortcut icon" href="{{ getImage(getFilePath('logoIcon') . '/favicon.jpeg') }}" type="image/x-icon">

    {{--<!-- Apple Stuff -->--}}
    <link rel="apple-touch-icon" href="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $general->sitename($pageTitle) }}">
    {{--<!-- Google / Search Engine Tags -->--}}
    <meta itemprop="name" content="{{ $general->sitename($pageTitle) }}">
    <meta itemprop="description" content="{{ $seoContents->description }}">
    <meta itemprop="image" content="{{ $seoContents->image }}">
    {{--<!-- Facebook Meta Tags -->--}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoContents->social_title }}">
    <meta property="og:description" content="{{ $seoContents->social_description }}">
    <meta property="og:image" content="{{ $seoContents->image }}"/>
    <meta property="og:image:type" content="{{ @pathinfo($seoContents->image)['extension'] }}"/>
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}" />
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    {{--<!-- Twitter Meta Tags -->--}}
    <meta name="twitter:card" content="summary_large_image">
@endif