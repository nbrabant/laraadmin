<?php namespace Dwij\Laraadmin\Helpers;

use Dwij\Laraadmin\Models\ModuleFieldTypes;

trait TableShemaTraitHelper
{
	public static function create_field_schema($table, $field, $update = false, $isFieldTypeChange = false) {
		if(is_numeric($field->field_type)) {
			$ftypes = ModuleFieldTypes::getFTypes();
			$field->field_type = array_search($field->field_type, $ftypes);
		}
		if(!is_string($field->defaultvalue)) {
			$defval = json_encode($field->defaultvalue);
		} else {
			$defval = $field->defaultvalue;
		}
		\Log::debug('Module:create_field_schema ('.$update.') - '.$field->colname." - ".$field->field_type
				." - ".$defval." - ".$field->maxlength);

		// @TODO : really need to cleanup this process - using factory pattern?
		switch ($field->field_type) {
			case 'Address':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->text($field->colname)->change();
					} else {
						$var = $table->text($field->colname);
					}
				} else {
					if ($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}

				if ($field->required) {
					$var->default("");
				} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL') {
					$var->nullable()->default(null);
				} else {
					$var->default($field->defaultvalue);
				}
				break;
			case 'Checkbox':
				if ($update) {
					$var = $table->boolean($field->colname)->change();
				} else {
					$var = $table->boolean($field->colname);
				}

				if ($field->required) {
					$field->defaultvalue = false;
				} elseif ($field->defaultvalue == "true" || $field->defaultvalue == "false" || $field->defaultvalue == true || $field->defaultvalue == false) {
					if (is_string($field->defaultvalue)) {
						$field->defaultvalue = (bool)($field->defaultvalue == "true");
					}
					$var->default($field->defaultvalue);
				}
				break;
			case 'Currency':
				if($update) {
					$var = $table->double($field->colname, 15, 2)->change();
				} else {
					$var = $table->double($field->colname, 15, 2);
				}

				if ($field->required) {
					$var->default("0.0");
				} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL') {
					$var->nullable()->default(null);
				} else {
					$var->default($field->defaultvalue);
				}
				break;
			case 'Date':
				if($update) {
					$var = $table->date($field->colname)->change();
				} else {
					$var = $table->date($field->colname);
				}

				if($field->required) {
					$var->default("1970-01-01");
				} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL' || $field->defaultvalue === "") {
					$var->nullable()->default(null);
				} else if(!starts_with($field->defaultvalue, "date")) {
					$var->default($field->defaultvalue);
				}
				break;
			case 'Datetime':
				if($update) {
					// Timestamp Edit Not working - http://stackoverflow.com/questions/34774628/how-do-i-make-doctrine-support-timestamp-columns
					// Error Unknown column type "timestamp" requested. Any Doctrine type that you use has to be registered with \Doctrine\DBAL\Types\Type::addType()
					// $var = $table->timestamp($field->colname)->change();
				} else {
					$var = $table->timestamp($field->colname);
				}
				// $table->timestamp('created_at')->useCurrent();
				if($field->required) {
					$var->default("1970-01-01 01:01:01");
				} elseif (isset($var)) {
					if (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL' || $field->defaultvalue === "") {
						$var->nullable()->default(null);
					} else if (!starts_with($field->defaultvalue, "date")) {
						$var->default($field->defaultvalue);
					}
				}
				break;
			case 'Decimal':
				$var = null;
				if($update) {
					$var = $table->decimal($field->colname, 15, 3)->change();
				} else {
					$var = $table->decimal($field->colname, 15, 3);
				}

				if ($field->required) {
					$var->default("0.0");
				} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL') {
					$var->nullable()->default(null);
				} else {
					$var->default($field->defaultvalue);
				}
				break;
			case 'Dropdown':
				if($field->popup_vals == "") {
					if(is_int($field->defaultvalue)) {
						if($update) {
							$var = $table->integer($field->colname)->unsigned()->change();
						} else {
							$var = $table->integer($field->colname)->unsigned();
						}
						$var->default($field->defaultvalue);
						break;
					} else if(is_string($field->defaultvalue)) {
						if($update) {
							$var = $table->string($field->colname)->change();
						} else {
							$var = $table->string($field->colname);
						}
						$var->default($field->defaultvalue);
						break;
					}
				}
				$popup_vals = json_decode($field->popup_vals);
				if(starts_with($field->popup_vals, "@")) {
					$foreign_table_name = str_replace("@", "", $field->popup_vals);
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
						if($field->defaultvalue == "" || $field->defaultvalue == "0") {
							$var->default(1);
						} else {
							$var->default($field->defaultvalue);
						}
						$table->dropForeign($field->module_obj->name_db."_".$field->colname."_foreign");
						$table->foreign($field->colname)->references('id')->on($foreign_table_name);
					} else {
						$var = $table->integer($field->colname)->unsigned();
						if($field->defaultvalue == "" || $field->defaultvalue == "0") {
							$var->default(1);
						} else {
							$var->default($field->defaultvalue);
						}
						$table->foreign($field->colname)->references('id')->on($foreign_table_name);
					}
				} else if(is_array($popup_vals)) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
					if($field->defaultvalue != "") {
						$var->default($field->defaultvalue);
					} else if($field->required) {
						$var->default("");
					}
				} else if(is_object($popup_vals)) {
					// ############### Remaining
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					// if(is_int($field->defaultvalue)) {
					//     $var->default($field->defaultvalue);
					//     break;
					// }
				}
				break;
			case 'Email':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname, 100)->change();
					} else {
						$var = $table->string($field->colname, 100);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}

				if ($field->required) {
					$var->default("");
				} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL') {
					$var->nullable()->default(null);
				} else {
					$var->default($field->defaultvalue);
				}
				break;
			case 'File':
				if($update) {
					$var = $table->integer($field->colname)->change();
				} else {
					$var = $table->integer($field->colname);
				}
				if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(0);
				}
				break;
			case 'Files':
				if($update) {
					$var = $table->string($field->colname, 256)->change();
				} else {
					$var = $table->string($field->colname, 256);
				}
				if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$var->default($field->defaultvalue);
				} else if(is_array($field->defaultvalue)) {
					$var->default(json_encode($field->defaultvalue));
				} else {
					$var->default("[]");
				}
				break;
			case 'Float':
				if($update) {
					$var = $table->float($field->colname)->change();
				} else {
					$var = $table->float($field->colname);
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("0.0");
				}
				break;
			case 'HTML':
				if($update) {
					$var = $table->string($field->colname, 10000)->change();
				} else {
					$var = $table->string($field->colname, 10000);
				}
				if($field->defaultvalue != null) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Image':
				if($update) {
					$var = $table->integer($field->colname)->change();
				} else {
					$var = $table->integer($field->colname);
				}
				if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(1);
				}
				break;
			case 'Integer':
				$var = null;
				if($update) {
					$var = $table->integer($field->colname, false)->unsigned()->change();
				} else {
					$var = $table->integer($field->colname, false)->unsigned();
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("0");
				}
				break;
			case 'Mobile':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Multiselect':
				if($update) {
					$var = $table->string($field->colname, 256)->change();
				} else {
					$var = $table->string($field->colname, 256);
				}
				if(is_array($field->defaultvalue)) {
					$field->defaultvalue = json_encode($field->defaultvalue);
					$var->default($field->defaultvalue);
				} else if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$var->default($field->defaultvalue);
				} else if($field->defaultvalue == "" || $field->defaultvalue == null) {
					$var->default("[]");
				} else if(is_string($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					$var->default($field->defaultvalue);
				} else if(is_int($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					//echo "int: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("[]");
				}
				break;
			case 'Name':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Password':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Radio':
				$var = null;
				if($field->popup_vals == "") {
					if(is_int($field->defaultvalue)) {
						if($update) {
							$var = $table->integer($field->colname)->unsigned()->change();
						} else {
							$var = $table->integer($field->colname)->unsigned();
						}
						$var->default($field->defaultvalue);
						break;
					} else if(is_string($field->defaultvalue)) {
						if($update) {
							$var = $table->string($field->colname)->change();
						} else {
							$var = $table->string($field->colname);
						}
						$var->default($field->defaultvalue);
						break;
					}
				}
				if(is_string($field->popup_vals) && starts_with($field->popup_vals, "@")) {
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					break;
				}
				$popup_vals = json_decode($field->popup_vals);
				if(is_array($popup_vals)) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
					if($field->defaultvalue != "") {
						$var->default($field->defaultvalue);
					} else if($field->required) {
						$var->default("");
					}
				} else if(is_object($popup_vals)) {
					// ############### Remaining
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					// if(is_int($field->defaultvalue)) {
					//     $var->default($field->defaultvalue);
					//     break;
					// }
				}
				break;
			case 'String':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != null) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Taginput':
				$var = null;
				if($update) {
					$var = $table->string($field->colname, 1000)->change();
				} else {
					$var = $table->string($field->colname, 1000);
				}
				if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$field->defaultvalue = json_decode($field->defaultvalue);
				}

				if(is_string($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					//echo "string: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if(is_array($field->defaultvalue)) {
					$field->defaultvalue = json_encode($field->defaultvalue);
					//echo "array: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Textarea':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->text($field->colname)->change();
					} else {
						$var = $table->text($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}

					if($field->required) {
						$var->default("");
					} elseif (is_null($field->defaultvalue) || $field->defaultvalue == 'NULL') {
						$var->nullable()->default(null);
					} else {
						$var->default($field->defaultvalue);
					}
				}
				break;
			case 'TextField':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'URL':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
		}

		// set column unique
		// @TODO test : unique column key must be defined on the migration, not on the shema helper
		// if($update) {
		// 	if($isFieldTypeChange) {
		// 		if($field->unique && $var != null && $field->maxlength < 256) {
		// 			$table->unique($field->colname);
		// 		}
		// 	}
		// } else {
		// 	if($field->unique && $var != null && $field->maxlength < 256) {
		// 		$table->unique($field->colname);
		// 	}
		// }
	}
}
