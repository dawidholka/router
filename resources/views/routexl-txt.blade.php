@START@54.604047,18.363044
@foreach($route->waypoints as $waypoint)
{{'@'.$waypoint->point->name}}@ {{$waypoint->point->lat}},{{$waypoint->point->long}} {{'{'.$waypoint->id.'}'}}
@endforeach
{{ $end ?? '' }}
