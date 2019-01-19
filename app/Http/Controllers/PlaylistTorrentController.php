<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\PlaylistTorrent;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;

class PlaylistTorrentController extends Controller
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
     * Attach A Torrent To A Playlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Playlist  $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $user = auth()->user();
        $playlist = Playlist::findOrFail($id);

        abort_unless($user->id === $playlist->user_id, 403);

        $playlist_torrent = new PlaylistTorrent();
        $playlist_torrent->playlist_id = $playlist->id;
        $playlist_torrent->torrent_id = $request->input('torrent_id');
        $playlist_torrent->user_id = $user->id;

        $v = validator($playlist_torrent->toArray(), [
            'playlist_id'    => 'required|numeric|exists:playlists,id',
            'torrent_id'    => 'required|numeric|exists:torrents,id',
            'user_id'     => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('playlist.show', ['id' => $playlist->id])
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $playlist_torrent->save();

            return redirect()->route('playlist.show', ['id' => $playlist->id])
                ->with($this->toastr->success('Playlist torrent has successfully been attached to your playlist.', 'Yay!', ['options']));
        }
    }

    /**
     * Detach A Torrent From A Playlist.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $playlist_torrent = PlaylistTorrent::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $playlist_torrent->user_id, 403);
        $playlist_torrent->delete();

        return redirect()->route('playlist.index')
            ->with($this->toastr->success('Playlist torrent has successfully been detached from your playlist.', 'Yay!', ['options']));
    }
}
