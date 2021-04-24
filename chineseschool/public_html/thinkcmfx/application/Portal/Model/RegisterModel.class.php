<?php

namespace Portal\Model;



use Common\Model\CommonModel;



class RegisterModel extends CommonModel

{

	//自动验证

	protected $_validate = array(

		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)

				array("english_name", "require", "english_name cannot be empty", 1 ),

				//array("chinese_name", "require", "chinese_name", 1),

				array("class", "require", "class cannot be empty", 1),

				//array("sex", "require", "性别不能为空", 1),

				array("birth_date", "require", "birth_date cannot be empty", 1),

				array("home_address", "require", "home_address cannot be empty", 1),

				//array("parent_sign", "require", "父母签名不能为空", 1),

				array("telphone", "require", "telphone cannot be empty", 1),

				array("post_code", "require", 'post_code cannot be empty',1),

				array("parent_name", "require", 'parent_name cannot be empty'),

				array("email", "require", "Email Address cannot be empty"),
				array("email","email","email format error"),
				array("emergency", "require", "Emergency cannot be empty")			

		);

}