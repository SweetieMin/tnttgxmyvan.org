<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SupportController extends Controller
{
    public function feedbackView()
    {
        SeoService::setDefaultSeo('Góp ý hệ thống');
        $data = [
            'pageTitle' => 'Góp ý hệ thống',
        ];
        return view('back.support.feedback', $data);
    }

    public function uploadImageFeedback(Request $request)
    {
        $file = $request->file('file');
        $uploadPath = public_path('/images/feedbacks');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '-' . uniqid() . '.' . $extension;

        $maxWidth = 1080;
        $maxHeight = 1080;

        $image = Image::make($file->path())
            ->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio(); 
                $constraint->upsize(); 
            })
            ->save($uploadPath . '/' . $fileName);

        return response()->json(['fileName' => $fileName]);
    }


    public function deleteImageFeedback(Request $request)
    {
        $fileName = $request->fileName;
        $path = public_path('images/feedbacks/' . $fileName);

        if (file_exists($path)) {
            unlink($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File không tồn tại']);
    }

    public function complaintView()
    {
        SeoService::setDefaultSeo('Khiếu nại');
        $data = [
            'pageTitle' => 'Khiếu nại',
        ];
        return view('back.support.complaint', $data);
    }

        public function uploadImageComplaint(Request $request)
    {
        $file = $request->file('file');
        $uploadPath = public_path('/images/complaints');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = $originalName . '-' . uniqid() . '.' . $extension;

        $maxWidth = 1080;
        $maxHeight = 1080;

        $image = Image::make($file->path())
            ->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio(); 
                $constraint->upsize(); 
            })
            ->save($uploadPath . '/' . $fileName);

        return response()->json(['fileName' => $fileName]);
    }


    public function deleteImageComplaint(Request $request)
    {
        $fileName = $request->fileName;
        $path = public_path('images/complaints/' . $fileName);

        if (file_exists($path)) {
            unlink($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File không tồn tại']);
    }

    public function assignedView()
    {
        SeoService::setDefaultSeo('Phân công giải quyết');
        $data = [
            'pageTitle' => 'Phân công giải quyết',
        ];
        return view('back.support.assigned', $data);
    }

    public function resolveView()
    {
        SeoService::setDefaultSeo('Giải quyết khiếu nại');
        $data = [
            'pageTitle' => 'Giải quyết khiếu nại',
        ];
        return view('back.support.resolve', $data);
    }
}
