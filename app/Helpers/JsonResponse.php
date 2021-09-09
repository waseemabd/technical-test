<?php


namespace App\Helpers;


class JsonResponse
{
    const MSG_ADDED_SUCCESSFULLY = 'added_successfully';
    const MSG_UPDATED_SUCCESSFULLY = "updated_successfully";
    const MSG_DELETED_SUCCESSFULLY = "deleted_successfully";
    const MSG_DELETE_ERROR_USED_RESOURCE = "delete_error_used_resource";
    const MSG_UPDATE_ERROR_USED_RESOURCE = "update_error_used_resource";
    const MSG_NOT_ALLOWED = "not_allowed";
    const MSG_NOT_AUTHORIZED = "not_authorized";
    const MSG_NOT_AUTHENTICATED = "not_authenticated";
    const MSG_NOT_FOUND = "not_found";
    const MSG_USER_NOT_FOUND = "user_not_found";
    const MSG_USER_NOT_ENABLED = "user_not_enabled";
    const MSG_WRONG_PASSWORD = "wrong_password";
    const MSG_SUCCESS = "success";
    const MSG_FAILED = "failed";
    const MSG_LOGIN_SUCCESSFULLY = "login_successfully";
    const MSG_LOGIN_FAILED = "login_failed";
    const MSG_FILE_NOT_FOUND = "file_not_found";
    const MSG_NOT_FOUND_MODIFICATION = "not_found_modification";
    const MSG_NOT_FOUND_VERSION = "not_found_version";
    const MSG_NOT_FOUND_NOTE = "not_found_note";
    const MSG_NOT_FOUND_PROJECT = "not_found_project";
    const MSG_INVALID_INPUTS = "invalid_input";
    const MSG_REQUIRED = "required";
    const MSG_OPERATION_ALREADY_DONE = "operation_already_done";

    /**
     * @param $message
     * @param null $content
     * @param int $status
     * @param string $conventionType
     * @return \Illuminate\Http\JsonResponse
     */
    public static function respondSuccess($message, $content = null, $conventionType = Constants::CONV_CAMEL, $status = 200)
    {
        $contentData = null;

        if (!is_null($content)) {
            switch ($conventionType) {
                case Constants::CONV_CAMEL:
                    $contentData = Mapper::toCamel($content);
                    break;
                case Constants:: CONV_UNDERSCORE:
                    $contentData = $content;
                    break;
                default:
                    $contentData = $content;
            }
        }
        return response()->json([
            'result' => 'success',
            'content' => $contentData,
            'message' => $message,
            'status' => $status
        ]);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function respondError($message, $status = 500)
    {
        return response()->json([
            'result' => 'failed',
            'content' => null,
            'message' => $message,
            'status' => $status
        ]);
    }

    public static function downloadFile($url)
    {
        return response()->download(public_path('storage/' . $url));
    }

    public static function downloadProject($zipName)
    {
        $headers = ['Content-Type: application/zip'];
        return response()->download($zipName, '', $headers);
    }

    public static function uploadFile($url)
    {

    }
}
