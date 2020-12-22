<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Contact;

class ContactController extends Controller
{
    protected $baseUrl;
    public function __construct(UrlGenerator $urlGenerator){

        $this->middleware('auth:users');
        $this->baseUrl = $urlGenerator->to('/');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allContacts($token)
    {

        $fileDirectory  = $this->baseUrl.'/contacts/images'; 
        $user           = auth('users')->authenticate($token);
        $userId         = $user->id;
        $contacts       = Contact::where('user_id',$userId)->orderBy('id','DESC');
        $totalContacts  = $contacts->count();
        $contacts       = $contacts->get();

        $searchTerm = $_REQUEST['q'];

        if ( !empty($searchTerm) ) {
            $contacts = Contact::where('first_name','like',"%$searchTerm%")
                        ->orWhere('last_name', 'like', "%$searchTerm%")
                        ->orWhere('email_address', 'like', "%$searchTerm%")
                        ->orWhere('phone_number', 'like', "%$searchTerm%")
                        ->orderBy('id','DESC')->get();
        }
        
        return response()->json([
            'success'        =>  true,
            'contacts'       =>  $contacts,
            'file_directory' =>  $fileDirectory,
            'totalContacts'  =>  $totalContacts
        ],200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'token'         => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email_address' => 'required|email',
            'phone_number'  => 'required',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray()

            ],400);
        }

        //image validation
        $image_name_to_store = '';
        $image = $request->file('image');
        if ($image) {
            //full image name with extension
            $image_name_with_ext = $image->getClientOriginalName();

            //image name without extension
            $image_name_without_ext = pathinfo($image_name_with_ext, PATHINFO_FILENAME);

            //get extension of that image
            $image_ext = $image->getClientOriginalExtension();

            //image name with encription
            $image_name_to_store = $image_name_without_ext . '_' . time() . '_.' . $image_ext;

            //public folder
            $path = public_path('/contacts/images');
            $image->move($path, $image_name_to_store);
        }

        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);
        $userId    = $user->id;

        Contact::create([
           'user_id'        => $userId,
           'first_name'     => $request->first_name,    
           'last_name'      => $request->last_name,     
           'email_address'  => $request->email_address,
           'phone_number'   => $request->phone_number, 
           'image'          => $image_name_to_store
        ]);

        return response()->json([

            'success'   => true,
            'message'   => 'Contact Saved Successfully!'

        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$token)
    {

        $contact = Contact::find($id);
        if(!$contact) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Contact!'
            ],400);

        }

        $fileDirectory  = $this->baseUrl.'/contacts/images'; 

        return response()->json([
            'success'   => true,
            'errors'    => [],
            'message'   => '',
            'contact'   => $contact,
            'file_directory' =>  $fileDirectory
        ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[

            'token'         => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email_address' => 'required|email',
            'phone_number'  => 'required',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray()

            ],400);
        }

        $contact = Contact::find($id);
        if(!$contact) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Contact!'
            ],400);

        }

        //image validation
        $image_name_to_store = '';
        $image = $request->file('image');
        if ($image) {
            //full image name with extension
            $image_name_with_ext = $image->getClientOriginalName();

            //image name without extension
            $image_name_without_ext = pathinfo($image_name_with_ext, PATHINFO_FILENAME);

            //get extension of that image
            $image_ext = $image->getClientOriginalExtension();

            //image name with encription
            $image_name_to_store = $image_name_without_ext . '_' . time() . '_.' . $image_ext;

            //public folder
            $path = public_path('/contacts/images');
            $image->move($path, $image_name_to_store);
        }

        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);
        $userId    = $user->id;

        $contact->update([
           'user_id'        => $userId,
           'first_name'     => $request->first_name,    
           'last_name'      => $request->last_name,     
           'email_address'  => $request->email_address,
           'phone_number'   => $request->phone_number, 
           'image'     => $image_name_to_store
        ]);

        return response()->json([

            'success'   => true,
            'message'   => 'Contact Updated Successfully!'

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if(!$contact) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Contact!'
            ],400);

        }

        $getFile = $contact->image;
        if($contact->delete()){

            if ($getFile) {
                unlink( public_path() . '/contacts/images/'.$getFile );
            }
            
            return response()->json([
                'success'   => true,
                'message'   => 'Contact Deleted Successfully!'
            ],200);

        }

    }
}
