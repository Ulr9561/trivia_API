<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use Illuminate\Console\Command;
use MongoDB\BSON\ObjectId;

class AssignUsersToQuizzes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:users-to-quizzes';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign users to quizzes';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $defaultUserId = new ObjectId('66894b837880c651150de138');

        Quiz::whereNotNull('user_id')->update(['user_id' => $defaultUserId]);

        $this->info('Users assigned to quizzes successfully.');
    }
}
