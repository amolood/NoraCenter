<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
  	use CB;
  	use Illuminate\Support\Facades\Route;
	use CRUDBooster;
  	use NoraCenter;
  	use Carbon\Carbon;

	class AdminTraineeController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = true;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "cms_users";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

      		$trainee_url = url(config('crudbooster.ADMIN_PATH')).'/trainee';

      		# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ID-NO","name"=>"id"];
			$this->col[] = ["label" => "Name", "name" => "name", "callback_php" => '"<a href=\"'.$trainee_url.'/detail/$row->id\">$row->name</a>"'];
			// $this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Photo","name"=>"photo","image"=>true];
			$this->col[] = ["label"=>"Email","name"=>"email"];
			$this->col[] = ["label"=>"Phone Number","name"=>"phone_number"];
			$this->col[] = ["label"=>"Educational Level","name"=>"educational_level"];
			$this->col[] = ["label"=>"Address","name"=>"address"];
			# END COLUMNS DO NOT REMOVE THIS LINE


			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Photo','name'=>'photo','type'=>'upload','validation'=>'image|max:6000','width'=>'col-sm-10','help'=>'File types support : JPG, JPEG, PNG, GIF, BMP'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|email|unique:cms_users','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			$this->form[] = ['label'=>'Phone Number','name'=>'phone_number','type'=>'number','validation'=>'required|unique:cms_users|numeric|min:10','width'=>'col-sm-8','help'=>'09×××××××× / 01×××××××× | رقم الهاتف'];
			$this->form[] = ['label'=>'Gender','name'=>'gender','type'=>'select','validation'=>'required','width'=>'col-sm-9','dataenum'=>'ذكر;أنثى'];
			$this->form[] = ['label'=>'Specialization','name'=>'specialization','type'=>'text','validation'=>'required','width'=>'col-sm-9','help'=>'التخصص'];
			$this->form[] = ['label'=>'Educational Level','name'=>'educational_level','type'=>'select','validation'=>'required','width'=>'col-sm-9','dataenum'=>'أمي;أساسي;ثانوي;جامعي;فوق الجامعي','help'=>'المستوى التعليمي'];
			$this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required|string','width'=>'col-sm-9','help'=>'العنوان'];
			$this->form[] = ['label'=>'Password','name'=>'password','type'=>'password','validation'=>'min:3|max:32','width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			// $this->form = [];
			// $this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			// $this->form[] = ['label'=>'Name English','name'=>'name_english','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Photo','name'=>'photo','type'=>'upload','validation'=>'required|image|max:3000','width'=>'col-sm-10','help'=>'File types support : JPG, JPEG, PNG, GIF, BMP'];
			// $this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:cms_users','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			// $this->form[] = ['label'=>'Password','name'=>'password','type'=>'password','validation'=>'min:3|max:32','width'=>'col-sm-10','help'=>'Minimum 5 characters. Please leave empty if you did not change the password.'];
			// $this->form[] = ['label'=>'Cms Privileges','name'=>'id_cms_privileges','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'cms_privileges,name'];
			// $this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Phone Number','name'=>'phone_number','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10','placeholder'=>'You can only enter the number only'];
			// $this->form[] = ['label'=>'Gender','name'=>'gender','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Age','name'=>'age','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Specialization','name'=>'specialization','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Educational Level','name'=>'educational_level','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Address','name'=>'address','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Details','name'=>'details','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			# OLD END FORM

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
	        $this->addaction = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert        = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
          $query->where('cms_users.id_cms_privileges',7);
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
          $postdata['id_cms_privileges'] = 7;
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getDetail($id) {
			$this->cbLoader();
			$module = CRUDBooster::getCurrentModule();
			$row = DB::table($this->table)->where($this->primary_key, $id)->first();
			if (NoraCenter::isTrainee() && CRUDBooster::myId() != $id) {
				CRUDBooster::insertLog(trans('crudbooster.log_try_view', ['name' => $row->{$this->title_field},'module' => $module->name]));
				CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			// $this->cbLoader();
			$row = DB::table($this->table)->where($this->primary_key, $id)->first();

			if (! CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {
			CRUDBooster::insertLog(trans("crudbooster.log_try_view", [
				'name' => $row->{$this->title_field},
				'module' => CRUDBooster::getCurrentModule()->name,
			]));
			CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}

			$module = CRUDBooster::getCurrentModule();

			$page_menu = Route::getCurrentRoute()->getActionName();
			$page_title = trans("crudbooster.detail_data_page_title", ['module' => $module->name, 'name' => $row->{$this->title_field}]);
			$command = 'detail';

			Session::put('current_row_id', $id);
			$join_date = DB::table('cms_users')->where('id',$id)->value('created_at');
			$join_date = Carbon::parse($join_date)->format('jS \\of F Y');

			$groups_trainee = DB::table('groups_trainees')->where('trainees_id',$id)->where('register_fees',NULL)->get();
				foreach ($groups_trainee as $key => $value) {
				$groups_id[]=$value->groups_id;
				}
				if ($groups_id) {
				$register_fees = DB::table('groups')->whereIn('id',$groups_id)->sum('register_fees');
				}else {
				$register_fees = 0;
				}

			$disscount_values = DB::table('groups_trainees')
									->where('trainees_id',$id)
									->where('fees_remaining','>',0)
									->sum('disscount_value');

			$fees_remaining = DB::table('groups_trainees')
								->where('trainees_id',$id)
								->sum('fees_remaining');

			$fees_remaining = $fees_remaining - $disscount_values;

			$results = DB::table('groups_trainees')->where('trainees_id',$id)->get();
			$groups_trainee = [];
			foreach ($results as $key => $result) {
				$groups_trainee[$key]['group_name']           = DB::table('groups')->where('id',$result->groups_id)->value('name');
				$groups_trainee[$key]['groups_id']            = $result->groups_id;
				$groups_trainee[$key]['course_name']          = DB::table('courses')->where('id',DB::table('groups')->where('id',$result->groups_id)->value('courses_id'))->value('name');
				$groups_trainee[$key]['fees_paid']            = $result->fees - $result->fees_remaining;
				$groups_trainee[$key]['total_fees_remaining'] = $result->fees_remaining - $result->disscount_value;
				$attendances = DB::table('attendances')->where('groups_id',$result->groups_id)->first();
				$attended = DB::table('attendance_trainees')->where('attendances_id',DB::table('attendances')->where('groups_id',$result->groups_id)->value('id'))->where('trainees_id',$result->trainees_id)->where('status','attended')->count();
				$groups_trainee[$key]['attendances']          = $attended.' -of- '.$attendances->lectures_number;
				$groups_trainee[$key]['result']               = DB::table('certificates_details')->where('trainees_id',$result->trainees_id)->where('certificates_id',DB::table('certificates')->where('groups_id',$result->groups_id)->value('id'))->value('degree');
				$groups_trainee[$key]['certificate_status']   = DB::table('certificates_details')->where('certificates_id',DB::table('certificates')->where('groups_id',$result->groups_id)->value('id'))->where('trainees_id',$result->trainees_id)->value('certificate_status');
			}


			return view('trainee.profile', compact('row',
							'page_menu',
							'page_title',
							'command',
							'id',
							'join_date',
							'register_fees',
							'result',
							'groups_trainee',
							'fees_remaining'
						));
		}
	}
