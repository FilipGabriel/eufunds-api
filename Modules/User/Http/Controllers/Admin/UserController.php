<?php

namespace Modules\User\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Modules\Media\Entities\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Modules\Admin\Traits\HasCrudActions;
use Modules\User\Http\Requests\SaveUserRequest;

class UserController
{
    use HasCrudActions;

    /**
     * Model for the resource.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Label of the resource.
     *
     * @var string
     */
    protected $label = 'user::users.user';

    /**
     * View path of the resource.
     *
     * @var string
     */
    protected $viewPath = 'user::admin.users';

    /**
     * Form requests for the resource.
     *
     * @var array|string
     */
    protected $validation = SaveUserRequest::class;

    public function index(Request $request)
    {
        if ($request->has('query')) {
            return $this->getModel()
                ->search($request->get('query'))
                ->query()
                ->limit($request->get('limit', 10))
                ->get();
        }

        if ($request->has('table')) {
            return $this->getModel()->table($request);
        }

        return view("{$this->viewPath}.index");
    }

    /**
     * @param Request $request
     */
    public function import(Request $request)
    {
        if(! $request->has('file')) {
            return redirect(route('admin.users.index'))->with(['error' => 'Încarcă fișierul' ]);
        }

        try {
            $location = 'import';
            $file = $request->file('file');
            $path = Storage::disk('public_storage')->putFile($location, $file);

            $file = File::create([
                'user_id' => auth()->id(),
                'disk' => 'public_storage',
                'location' => $location,
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'extension' => $file->guessClientExtension() ?? '',
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            $spreadsheet = IOFactory::load($file->realPath());
            $data = $spreadsheet->getActiveSheet()->toArray();

            $users = [];
            foreach($data as $row) {
                $name = trim($row[0]);
                $email = trim($row[1]);
                if(! empty($email) && ! in_array($email, $users)) {
                    array_push($users, ['name' => $name, 'email' => $email, 'created_at' => now()]);
                }
            }

            User::insertOrIgnore($users);

            $file->delete();
        } catch(Exception $e) {
            return redirect(route('admin.users.index'))->with(['error' => $e->getMessage()]);
        }

        return redirect(route('admin.users.index'))->with(['success' => 'Fișierul a fost importat cu succes.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\User\Http\Requests\SaveUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveUserRequest $request)
    {
        $request->merge(['password' => bcrypt($request->password ?: 'doesntmatter')]);

        $user = User::create($request->all());

        $user->roles()->attach($request->roles);

        return redirect()->route('admin.users.index')
            ->withSuccess(trans('admin::messages.resource_saved', ['resource' => trans('user::users.user')]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param \Modules\User\Http\Requests\SaveUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, SaveUserRequest $request)
    {
        $user = User::findOrFail($id);

        if (is_null($request->password)) {
            unset($request['password']);
        } else {
            $request->merge(['password' => bcrypt($request->password)]);
        }

        $user->update($request->all());

        $user->roles()->sync($request->roles);

        return redirect()->route('admin.users.index')
            ->withSuccess(trans('admin::messages.resource_saved', ['resource' => trans('user::users.user')]));
    }

    /**
     * Destroy resources by given ids.
     *
     * @param string $ids
     * @return void
     */
    public function destroy($ids)
    {
        User::whereIn('id', explode(',', $ids))->get()->map(function($user) {
            $user->delete();
        });
    }
}
