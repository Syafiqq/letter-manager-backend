<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 01 April 2019, 10:21 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Student;


use App\Http\Controllers\Controller;
use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;

class LetterController extends Controller
{
    private $guard;

    /**
     * Auth constructor.
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function postStore(Request $request)
    {
        $vault = $this->validate($request, [
            'title' => 'bail|required',
            'code' => 'bail|required',
            'index' => 'bail|required',
            'number' => 'bail|required',
            'subject' => 'bail|required',
            'date' => 'bail|required|date_format:"Y-m-d H:i:s"',
            'kind' => "bail|required|in:incoming,outgoing",
            'file' => 'bail|required|file|mimetypes:application/pdf|mimes:pdf',
        ]);

        if ($request->has('file') && $request->file('file')->isValid())
        {
            /** @var UploadedFile $file */
            $file    = $request->file('file');
            $now     = Carbon::now(env('APP_TIMEZONE', 'UTC'));
            $dirname = $now->format('Ymd');
            $file->store("public/letters/{$dirname}");

            $user                 = new \App\Eloquents\Letter();
            $user->{'id'}         = Uuid::uuid1()->toString();
            $user->{'title'}      = $vault['title'];
            $user->{'code'}       = $vault['code'];
            $user->{'index'}      = $vault['index'];
            $user->{'number'}     = $vault['number'];
            $user->{'subject'}    = $vault['subject'];
            $user->{'date'}       = $vault['date'];
            $user->{'kind'}       = $vault['kind'];
            $user->{'file'}       = $file->path();
            $user->{'created_at'} = $now;
            $user->{'updated_at'} = $now;
            $user->{'issuer'}     = $this->guard->user()->getAuthIdentifier();
            $user->save();

            return response()->json(PopoMapper::alertResponse(HttpStatus::OK, 'Letter added successfully'), HttpStatus::OK);
        }

        return response()->json(PopoMapper::alertResponse(HttpStatus::UNPROCESSABLE_ENTITY, 'There was error when uploading files'), HttpStatus::UNPROCESSABLE_ENTITY);
    }
}

?>
