@extends('app')

@section('content')
    <style>
        .equal-height-cols{
            display: flex;
            flex-wrap: wrap;
            justify-content: stretch;
        }
    </style>

    <div class="row">

        <div class="col mb-4">

            <div class="col-12">
                <div class="card card mb-4">
                    <div class="card-header">
                        <b>Color Dominance</b>
                    </div>
                    <div class="card-body">

                        <canvas id="hits"></canvas>

                    </div>
                </div><!--card-->
            </div>

            <div class="col-12">
                <div class="card card mb-4">
                    <div class="card-header">
                        <b>Image Detection</b>
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Fidelity</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ai['webDetection']->webEntities as $tuple)
                                <tr>
                                    <th scope="row">{{$tuple->entityId}}</th>
                                    <td>{{isset($tuple->description)?$tuple->description:'-'}}</td>
                                    <td>{{$tuple->score}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div><!--card-->
            </div>

            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <b>Image Annotations</b>
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Score</th>
                                <th scope="col">Topicality</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ai['labelAnnotations'] as $tuple)
                                <tr>
                                    <th scope="row">{{$tuple->mid}}</th>
                                    <td>{{$tuple->description}}</td>
                                    <td>{{$tuple->score}}</td>
                                    <td>{{$tuple->topicality}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div><!--card-->
            </div><!--col-->

        </div><!--row-->


        <div class="col-6 mb-4">

            <div class="col-12">
                <img src="{{route('home').'/'.$id.'.jpg'}}" class="img-fluid mb-4">
            </div><!--col-->

            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <b>Object Description</b>
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Label</th>
                                <th scope="col">Confidence</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ai['localizedObjectAnnotations'] as $tuple)
                                <tr>
                                    <th scope="row">{{$tuple->mid}}</th>
                                    <td>{{$tuple->name}}</td>
                                    <td>{{$tuple->score}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div><!--card-->
            </div><!--col-->

            <div class="col-12">
                <div class="card card mb-4">
                    <div class="card-header">
                        <b>Safe Search Annotations</b>
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Param</th>
                                <th scope="col">Flag</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ai['safeSearchAnnotation'] as $key=>$value)
                                <tr>
                                    <th scope="row">{{ucfirst($key)}}</th>
                                    <td>{{ucfirst(strtolower(str_replace('_', ' ',$value)))}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div><!--card-->
            </div>


            @if(isset($ai['faceAnnotations']))
                <div class="col-12">
                    <div class="card card mb-4">
                        <div class="card-header">
                            <b>Face Detection</b>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                @foreach($ai['faceAnnotations'] as $tuple)
                                    <tr>
                                        <th scope="row">{{$tuple->boundingPoly->vertices[0]->x}}, {{$tuple->boundingPoly->vertices[0]->y}}</th>
                                        <td><table class="table">
                                                <tbody>
                                                @foreach($tuple as $key=>$value)
                                                    @if(!in_array($key, $exclude))
                                                        <tr>
                                                            <td scope="row">{{$key}}</td>
                                                            <td>{{ucfirst(strtolower(str_replace('_', ' ', $value)))}}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div><!--card-->
                </div>
            @endif

        </div><!--row-->

    </div>

    @push('scripts')
        <script>
            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        label: 'Top 3 Hits',
                        data: [
                            @foreach($ai['imagePropertiesAnnotation']->dominantColors->colors as $color)
                                '{{$color->percentRounded}}',
                            @endforeach
                        ],
                        backgroundColor: [
                            @foreach($ai['imagePropertiesAnnotation']->dominantColors->colors as $color)
                                '#{{$color->hex}}',
                            @endforeach
                        ],
                    }],
                    labels: [
                        @foreach($ai['imagePropertiesAnnotation']->dominantColors->colors as $color)
                            '{{$color->hex}}',
                        @endforeach
                    ]
                },
                options: {
                    responsive: true
                }
            };
            var ctx = document.getElementById('hits').getContext('2d');
            window.myPie = new Chart(ctx, config);
        </script>
    @endpush
@endsection