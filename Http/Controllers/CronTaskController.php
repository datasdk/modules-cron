<?php

namespace Modules\Cron\Http\Controllers;

use App\Http\Controllers\OrionBaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Studio\Totem\Http\Requests\TaskRequest;
use Studio\Totem\Task;
use Studio\Totem\Totem;
use Studio\Totem\Contracts\TaskInterface;

class CronTaskController extends OrionBaseController
{
    /**
     * @var TaskInterface
     */
    private TaskInterface $tasks;

    /**
     * TasksController constructor.
     *
     * @param  TaskInterface  $tasks
     */
    public function __construct(TaskInterface $tasks)
    {
        parent::__construct();

        $this->tasks = $tasks;
    }

    
    protected $model = Task::class;

    protected $request = TaskRequest::class;

    /**
     * Display a listing of the tasks.
     *
     * @param Request $req
     * @return View
     */
    public function index(Request $req): View
    {

        return view('cron::index');

    }

    /**
     * Show the form for creating a new task.
     *
     * @param Request $req
     * @return View
     */
    public function create(Request $req): View
    {

        $commands = Totem::getCommands()->map(function ($command) {
            return ['name' => $command->getName(), 'description' => $command->getDescription()];
        });


        return view('cron::create', [
            'commands' => $commands,
            'timezones' => timezone_identifiers_list(),
            'frequencies' => Totem::frequencies(),
        ]);

    }

    /**
     * Store a newly created task in storage.
     *
     * @param Request $req
     * @param  ...$args
     * @return RedirectResponse
     */
    public function store(Request $req, ...$args): RedirectResponse
    {

        try {

            $this->tasks->store($req->all());

            return redirect()
                ->route('cron.tasks.index')
                ->with('success', trans('cron::messages.success.create'));

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);

        }

    }

    /**
     * Display the specified task.
     *
     * @param Request $req
     * @param  ...$args
     * @return View
     */
    public function show(Request $req, ...$args): View
    {

        $id = $args[0];

        $task = Task::with('frequencies', 'results')->findOrFail($id);

        return view('cron::show', [
            'task' => $task,
        ]);

    }

    /**
     * Show the form for editing the specified task.
     *
     * @param Request $req
     * @param  ...$args
     * @return View
     */
    public function edit(Request $req, ...$args): View
    {

        $id = $args[0];
        $task = Task::with('frequencies')->findOrFail($id);


        $commands = Totem::getCommands()->map(function ($command) {
            return ['name' => $command->getName(), 'description' => $command->getDescription()];
        });


        return view('cron::edit', [
            'task' => $task,
            'commands' => $commands,
            'timezones' => timezone_identifiers_list(),
            'frequencies' => Totem::frequencies(),
        ]);

    }

    /**
     * Update the specified task in storage.
     *
     * @param Request $req
     * @param  ...$args
     * @return RedirectResponse
     */
    public function update(Request $req, ...$args): RedirectResponse
    {

        try {

            $id = $args[0];
            $task = Task::findOrFail($id);
            $task = $this->tasks->update($req->all(), $task);

            return redirect()->route('cron.tasks.index')
                ->with('success', trans('cron::messages.success.update'));

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);

        }

    }

    /**
     * Remove the specified task from storage.
     *
     * @param Request $req
     * @param  ...$args
     * @return RedirectResponse
     */
    public function destroy(Request $req, ...$args): RedirectResponse
    {

        $id = $args[0];
        $task = Task::findOrFail($id);
        

        $this->tasks->destroy($task);


        return redirect()
            ->route('cron.tasks.index')
            ->with('success', trans('cron::messages.success.delete'));

    }
}