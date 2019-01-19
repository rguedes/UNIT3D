<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\PlaylistTorrent;
use Image;
use App\Playlist;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * PlaylistController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Display All Playlists.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $playlists = Playlist::withCount('torrents')->latest()->paginate(25);

        return view('playlist.index', ['playlists' => $playlists]);
    }

    /**
     * Show Playlist Create Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('playlist.create');
    }

    /**
     * Store A New Playlist.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $playlist = new Playlist();
        $playlist->user_id = $user->id;
        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');

        $image = $request->file('cover_image');
        $filename = 'playlist-cover_'.uniqid().'.'.$image->getClientOriginalExtension();
        $path = public_path('/files/img/'.$filename);
        Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        $playlist->cover_image = $filename;

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = validator($playlist->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'cover_image' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlist.create')
                ->withInput()
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $playlist->save();

            return redirect()->route('playlist.show', ['id' => $playlist->id])
                ->with($this->toastr->success('Your playlist has successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Show A Playlist.
     *
     * @param  \App\Playlist  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $playlist = Playlist::with('torrents')->findOrFail($id);

        return view('playlist.show', ['playlist' => $playlist]);
    }

    /**
     * Show Playlist Update Form.
     *
     * @param  \App\Playlist  $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id == $playlist->user_id, 403);

        return view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update A Playlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Playlist  $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id == $playlist->user_id, 403);

        $playlist->user_id = $user->id;
        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');

        $image = $request->file('cover_image');
        $filename = 'playlist-cover_'.uniqid().'.'.$image->getClientOriginalExtension();
        $path = public_path('/files/img/'.$filename);
        Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
        $playlist->cover_image = $filename;

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = validator($playlist->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'cover_image' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlist.edit', ['id' => $playlist->id])
                ->withInput()
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $playlist->save();

            return redirect()->route('playlist.show', ['id' => $playlist->id])
                ->with($this->toastr->success('Your playlist has successfully published!', 'Yay!', ['options']));
        }
    }

    /**
     * Delete A Playlist.
     *
     * @param  \App\Playlist  $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $playlist = PlaylistTorrent::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id == $playlist->user_id, 403);

        $playlist->delete();

        return redirect()->route('playlist.index')
            ->with($this->toastr->success('RSS Feed Deleted!', 'Yay!', ['options']));
    }
}
