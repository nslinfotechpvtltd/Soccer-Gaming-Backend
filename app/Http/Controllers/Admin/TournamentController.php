<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tournament;
use Illuminate\Http\Request;
use DataTables;

class TournamentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
//    public function index(Request $request)
//    {
//        $keyword = $request->get('search');
//        $perPage = 25;
//
//        if (!empty($keyword)) {
//            $tournament = Tournament::where('name', 'LIKE', "%$keyword%")
//                ->orWhere('type', 'LIKE', "%$keyword%")
//                ->orWhere('number_of_players', 'LIKE', "%$keyword%")
//                ->orWhere('number_of_teams_per_player', 'LIKE', "%$keyword%")
//                ->orWhere('number_of_plays_against_each_team', 'LIKE', "%$keyword%")
//                ->orWhere('number_of_players_that_will_be_in_the_knockout_stage', 'LIKE', "%$keyword%")
//                ->orWhere('legs_per_match_in_knockout_stage', 'LIKE', "%$keyword%")
//                ->orWhere('number_of_legs_in_final', 'LIKE', "%$keyword%")
//                ->latest()->paginate($perPage);
//        } else {
//            $tournament = Tournament::latest()->paginate($perPage);
//        }
//
//        return view('admin.tournament.index', compact('tournament'));
//    }

    protected $__rulesforindex = ['name' => 'required', 'type' => 'required', 'number_of_players' => 'required', 'number_of_teams_per_player' => 'required', 'created_at' => 'required'];

    public function index(Request $request) {
        if ($request->ajax()) {
            $tournament = Tournament::latest();
            return Datatables::of($tournament)
                            ->addIndexColumn()
                            ->addColumn('action', function($item) {
                                $return = '';
                                $return .= " <a href=" . url('/admin/tournament/' . $item->id) . " title='View Tournament'><button class='btn btn-info btn-sm'><i class='fa fa-eye' aria-hidden='true'></i></button></a>";
                                $return .= " <a href=" . url('/admin/tournamentPlayers/' . $item->id) . " title='View Tournament Players'><button class='btn btn-info btn-sm'><i class='fa fa-gamepad' aria-hidden='true'></i></button></a>";
                                return $return;
                            })
                            ->rawColumns(['action', 'image'])
                            ->make(true);
        }
        return view('admin.tournament.index', ['rules' => array_keys($this->__rulesforindex)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('admin.tournament.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'number_of_players' => 'required',
            'number_of_teams_per_player' => 'required'
        ]);
        $requestData = $request->all();

        Tournament::create($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.show', compact('tournament'));
    }

    public function showTournamentPlayers($tournament_id) {

        $tournamentPlayers = \App\TournamentPlayerTeam::where('tournament_id', $tournament_id)->groupBy('player_id')->get();

        return view('admin.tournament.showplayers', compact('tournamentPlayers'));
    }

    public function showTournamentPlayerFixtures($player_id) {

        $tournamentfixtures = \App\TournamentFixture::where('player_id_1', $player_id)->orWhere('player_id_2', $player_id)->get();

        return view('admin.tournament.showfixtures', compact('tournamentfixtures'));
    }

    public function showTournamentPlayerFixturesReported(Request $request) {

        $tournamentfixtures = \App\TournamentFixture::where('state', '1')->get();

        return view('admin.tournament.showfixtures', compact('tournamentfixtures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $tournament = Tournament::findOrFail($id);

        return view('admin.tournament.edit', compact('tournament'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'number_of_players' => 'required',
            'number_of_teams_per_player' => 'required'
        ]);
        $requestData = $request->all();

        $tournament = Tournament::findOrFail($id);
        $tournament->update($requestData);

        return redirect('admin/tournament')->with('flash_message', 'Tournament updated!');
    }

    public function editTournamentFixture(Request $request, $fixture_id) {
//        dd($fixture_id);
        $tournamentfixture = \App\TournamentFixture::findOrFail($fixture_id);

        return view('admin.tournament.editfixture', compact('tournamentfixture'));
    }

    public function updateTournamentFixture(Request $request, $fixture_id) {
        $this->validate($request, [
            'player_id_1_score' => 'required',
            'player_id_2_score' => 'required'
        ]);
        $requestData = $request->all();

//        dd($requestData);
        $tournamentfixture = \App\TournamentFixture::findOrFail($fixture_id);
        if ($request->update == 'Update & Mark Unreport'):
            $tournamentfixture->state = '0';
            $tournamentfixture->save();
        endif;
        $tournamentfixture->update($requestData);

        return redirect()->back()->with('flash_message', 'Fixture updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Tournament::destroy($id);

        return redirect('admin/tournament')->with('flash_message', 'Tournament deleted!');
    }

    public function changeStatus(Request $request) {
        $tournamentfixture = \App\TournamentFixture::findOrFail($request->id);
        $tournamentfixture->state = '0';
        $tournamentfixture->save();
        return response()->json(["success" => true, 'message' => 'Tournament fixture updated!']);
    }

}
