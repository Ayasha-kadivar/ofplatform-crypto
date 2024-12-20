 @php
     $workContent = getContent('how_work.content', true);
     $workElement = getContent('how_work.element', orderById: true);
 @endphp
 <section class="how-to-profit section-common-bg pt-120 pb-120">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-xxl-10">
                 <div class="section_title_wrapper text-center">
                     <h2 class="section-title">
                         {{ __(@$workContent->data_values->heading) }}
                     </h2>
                     <p class="section-subtitle margin-0 mb-20">
                         {{ __(@$workContent->data_values->sub_heading) }}
                     </p>
                 </div>
             </div>
         </div>
     </div>

     <div class="container mt-30">
         <div class="row">
             <div class="col-12">
                 <div class="d-flex profit-main">
                     @foreach ($workElement as $work)
                         <div
                             class="profit-inner__itemtest-big {{ $loop->index % 2 == 0 ? 'profit-inner__itemtest-big2' : '' }} ">
                             <div class="profit-inner__itemtest">
                                 <h4>{{ $loop->index + 1 }}. {{ __(@$work->data_values->title) }} </h4>
                                 <p>{{ __(@$work->data_values->sub_title) }}</p>
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         </div>
     </div>
 </section>
