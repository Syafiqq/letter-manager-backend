<?php
/**
 * This <letter-manager-backend> project created by :
 * Name         : syafiq
 * Date / Time  : 01 April 2019, 10:21 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Student;


use App\Eloquents\Letter;
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
        $letter = new \App\Eloquents\Letter();
        $vault  = $this->validate($request, [
            'title' => 'bail|required',
            'code' => 'bail|required',
            'index' => 'bail|required',
            'number' => 'bail|required',
            'subject' => 'bail|required',
            'date' => 'bail|required|date_format:"' . $letter->getDateFormat() . '"',
            'kind' => 'bail|required|in:' . implode(',', Letter::letterKind),
            'upload' => 'bail|required|file|mimetypes:application/pdf|mimes:pdf',
        ]);

        if ($request->hasFile('upload') && $request->file('upload')->isValid())
        {
            /** @var UploadedFile $file */
            $file    = $request->file('upload');
            $now     = Carbon::now(env('APP_TIMEZONE', 'UTC'));
            $dirname = Carbon::createFromFormat($letter->getDateFormat(), $vault['date'])->format('Ymd');
            $file->store("public/letters/{$dirname}");

            $letter->{'id'}         = Uuid::uuid1()->toString();
            $letter->{'title'}      = $vault['title'];
            $letter->{'code'}       = $vault['code'];
            $letter->{'index'}      = $vault['index'];
            $letter->{'number'}     = $vault['number'];
            $letter->{'subject'}    = $vault['subject'];
            $letter->{'date'}       = $vault['date'];
            $letter->{'kind'}       = $vault['kind'];
            $letter->{'file'}       = $file->path();
            $letter->{'created_at'} = $now;
            $letter->{'updated_at'} = $now;
            $letter->{'issuer'}     = $this->guard->user()->getAuthIdentifier();
            $letter->save();

            return response()->json(PopoMapper::alertResponse(HttpStatus::OK, 'Letter added successfully'), HttpStatus::OK);
        }

        return response()->json(PopoMapper::alertResponse(HttpStatus::UNPROCESSABLE_ENTITY, 'There was error when uploading files'), HttpStatus::UNPROCESSABLE_ENTITY);
    }
}

?>
