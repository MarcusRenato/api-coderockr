<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * The Event instance
     *
     * @var Event
     */
    protected $event;

    /**
     * Create a new controller instance.
     *
     * @param Event $event
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = request()->only('date', 'region');

        $events = $this->event
            ->when(isset($filters['date']), function ($query) use ($filters) {
                return $query->whereDate('date', $filters['date']);
            })
            ->when(isset($filters['region']), function ($query) use ($filters) {
                return $query->where('place', 'LIKE', "%{$filters['region']}%");
            })
            ->paginate(10);

        return response()->json([
            'error' => false,
            'message' => '',
            'data' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'path' => $events->path(),
                'data' => new EventCollection($events)
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $data = $request->all();

        try {

            $user = auth()->user();

            $event = $user->events()->create($data);

            return response()->json([
                'error' => false,
                'message' => '',
                'data' => new EventResource($event)
            ], 201);
        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => 'Ocorreu um erro ao tentar criar o evento. Tente novamente.',
                'data' => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = $this->event->findOrFail($id);

        return response()->json([
            'error' => false,
            'message' => '',
            'data' => new EventResource($event)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, $id)
    {
        $event = $this->event->where([
            'id' => $id,
            'user_id' => auth()->id()
        ])->first() ?? abort(404);

        if (!$event->update($request->all())) {
            return response()->json([
                'error' => true,
                'message' => 'Ocorreu um erro ao tentar atualizar evento.',
                'data' => []
            ], 500);
        }

        return response()->json([
            'error' => false,
            'message' => '',
            'data' => new EventResource($event)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = auth()->user()->events()->findOrFail($id);

        if (!$event->delete()) {
            return response()->json([
                'error' => true,
                'message' => 'Ocorreu um erro ao tentar atualizar evento.',
                'data' => []
            ], 500);
        }

        return response()->json([], 204);
    }
}
