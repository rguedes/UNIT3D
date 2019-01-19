<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_torrents', function (Blueprint $table) {
            $table->integer('playlist_id')->unsigned();
            $table->integer('torrent_id')->unsigned();

            $table->foreign('playlist_id')->references('id')->on('playlists');
            $table->foreign('torrent_id')->references('id')->on('torrents');

            $table->primary(['playlist_id', 'torrent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_torrents');
    }
}
