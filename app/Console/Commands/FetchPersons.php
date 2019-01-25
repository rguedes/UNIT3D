<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Console\Commands;

use App\Jobs\FetchPerson;
use Illuminate\Console\Command;
use App\Services\FetchingService;

class FetchPersons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:persons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all persons from the TMDB API and store them in database (~100h! for 1,292,137 Persons)';

    /**
     * @var FetchingService
     */
    protected $fetchingService;

    /**
     * FetchPersons constructor.
     *
     * @param FetchingService $fetchingService
     */
    public function __construct(FetchingService $fetchingService)
    {
        parent::__construct();
        $this->fetchingService = $fetchingService;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->line('Fetching latest person export from TMDB...');
        $this->fetchingService->fetchFile('person');
        $this->line('Extracting...');
        $this->fetchingService->extract('person');
        $this->info('Successfully extracted the person list.');
        $this->line('Queueing fetching jobs...');
        $this->fetchingService->fetch('person', FetchPerson::class);
        $this->fetchingService->deleteFiles('person');
        $this->info('Deleted downloaded files. The fetching of persons is queued and will take around 100 hours to complete');
    }
}