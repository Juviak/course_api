<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\capability;
use App\Models\course;
use App\Models\skill;
use Illuminate\Http\Request;

class courseApiController extends Controller
{

    public function index(){

        $courses = course::with('capabilities.skills')->paginate(10);

        $courseList = [
            'data' => $courses->items(),
            'pageable' => [
                'total' => $courses->total(),
                'limit' => $courses->perPage(),
                'page' => $courses->currentPage(),
            ],
        ];
      
        
        return response()->json([$courseList], 200);
    }

    public function store(Request $request){

         // Validating the request data
         $validator = Validator::make($request->all(), [
            'courseName'=> 'required|string',
            'courseImage'=>'nullable|image',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'capability.*.capabilityName'=>'required|string',
            'capability.*.skill.*.skillName'=>'required|string'
        ]);
        
      

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'message' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated(); 
        
        if($request->hasFile('courseImage')){

            $courseImage = $request->file('courseImage');
            $courseImageBinary = file_get_contents($courseImage->getRealPath());
        }
        else {
            $courseImageBinary = null; 
        }

        // storing the course table data
        $course = new course();
        $course->courseName=$validatedData['courseName'];
        $course->startDate=$validatedData['startDate'];
        $course->endDate=$validatedData['endDate'];
        $course->courseImage=$courseImageBinary;
        $course->save();


        // storing associated data (capabilities and skills)
        foreach($validatedData['capability'] as $capabilityData){
            $capability = new capability();
            $capability->capabilityName=$capabilityData['capabilityName'];
            $capability->courseId=$course->id;
            $capability->save();

              // Updating associated skills
            foreach($capabilityData['skill'] as $skillData){
                $skill=new skill();
                $skill->skillName=$skillData['skillName'];
                $skill->capabilityId=$capability->id;
                $skill->courseId=$course->id;
                $skill->save();
            }

        }

        return response()->json(['status' => 201,'data' => 'Created Successfully'],201);


    }

  

    public function show($id){
        $coursedetails=course::with('capabilities.skills')->find($id);

        if (!$coursedetails) {
            return response()->json(['status' => 404, 'message' => 'Course not found'], 404);
        }
        return response()->json(['status'=>200,'data' => $coursedetails], 200);

    }


    public function update(Request $request, $id) {
        
        // Validating the request data
        $validator = Validator::make($request->all(), [
            'courseName'=> 'required|string',
            'courseImage'=>'nullable|image',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'capability.*.id' => 'required|exists:capabilities,id', 
            'capability.*.capabilityName' => 'required|string',
            'capability.*.skill.*.id' => 'required|exists:skills,id', 
            'capability.*.skill.*.skillName' => 'required|string'
        ]);
        
      

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'message' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated(); 
           
        
    
        // Retrieving the record to update along with its associated data
        $course = Course::with('capabilities.skills')->findOrFail($id);
    
        // Updating the course table data
        $course->courseName = $validatedData['courseName'];
        $course->startDate = $validatedData['startDate'];
        $course->endDate = $validatedData['endDate'];
    
        
        if ($request->hasFile('courseImage')) {
            $courseImage = $request->file('courseImage');
            $courseImageBinary = file_get_contents($courseImage->getRealPath());
            $course->courseImage = $courseImageBinary;
        }
    
        $course->save();
    
        // Updating associated data (capabilities and skills)
        foreach ($validatedData['capability'] as $capabilityData) {
            $capability = $course->capabilities->where('id', $capabilityData['id'])->first();
            if ($capability) {
                $capability->capabilityName = $capabilityData['capabilityName'];
                $capability->save();
    
                // Updating associated skills
                foreach ($capabilityData['skill'] as $skillData) {
                    $skill = $capability->skills->where('id', $skillData['id'])->first();
                    if ($skill) {
                        $skill->skillName = $skillData['skillName'];
                        $skill->save();
                    }
                }
            }
        }
    
        return response()->json(['status' => 200,'data' => 'Updated Successfully'], 200);
    }
    

    public function destroy($id){
        $deleteCourse= course::find($id);

        if (!$deleteCourse) {
            return response()->json(['status' => 404, 'message' => 'Course not found'], 404);
        }

        $deleteCourse->delete();
        return response()->json(['status' => 200,'message' => 'Data deleted successfully'], 200);
    }

   
    
}
