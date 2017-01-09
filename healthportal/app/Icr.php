<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use App\SyncModel;
use App\Shclinic;
use App\Api\HealthNetworkClient;

use Collective\Html\Eloquent\FormAccessible;

class Icr extends SyncModel
{
    use FormAccessible, Traits\FileUpload;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'icr';
    public $timestamps = true;
    protected $guarded = ['age', 'birthdate', 'client_id', 'usrimage'];

    private $tests = array(
        'syp_scr' => array(
            'syp_scr_res', 'syp_scr_inf', 'syp_scr_prev', 'syp_scr_prev_cont', 'syp_scr_treat'),
        'syp_conf' => array(
            'syp_conf_res', 'syp_conf_inf', 'syp_conf_prev', 'syp_conf_prev_cont', 'syp_conf_treat'),
        'gram_stain' => array(
            'gram_stain_inf',
            'gono_prev', 'gono_cont', 'gono_treat',
            'ngi_prev', 'ngi_cont',
            'no_evidence_res', 'gono_res', 'ngi_res',
            'bacvag_insp', 'bacvag_res', 'bacvag_prev', 'bacvag_cont', 'bacvag_treat',
        ),
        'wet_mount' => array(
            'tri_res', 'tri_prev', 'tri_inf', 'tri_cont', 'tri_treat'),
        'hbsag' => array(
            'hepab_res', 'hepab_inf', 'hepab_vac'),
        'hepac' => array(
            'hepac_res', 'hepac_inf'),
        'inspected' => array(
            'gen_warts_insp', 'gen_warts_prev', 'gen_warts_cont', 'gen_warts_res',
            'anal_warts_insp', 'anal_warts_prev', 'anal_warts_cont', 'anal_warts_res',
            'herpes_insp', 'herpes_cont', 'herpes_prev', 'herpres_res', 'herpes_treat'
        ),
    );

    protected $rules = array(
        'consult_date' => 'required',
        'uic' => 'required|max:15',

        'consulttype' => 'required',
        'firstname' => 'required|max:50',
        'middlename' => 'max:50',
        'lastname' => 'required|max:50',
        'birthdate' => 'required|date',
        'sex' => 'required',
        'gender_identity' => 'required',

        'is_resident' => 'required',
        'municipality' => 'required|max:3',
        'province' => 'required|max:3',
        'region' => 'required|max:3',

        'is_perm_resident' => 'required',

        'rg_others_text' => 'max:100',

        'client_ref_reason' => 'max:50',
        'cr_others_text' => 'max:100',

        'establishment' => 'max:100',
        'est_type' => 'max:100',
    );

    private $checkboxes = array(
        'riskgroup' => array(
            'rg_rsw', 'rg_nsw', 'rg_fsw',
            'rg_msm', 'rg_tg', 'rg_pwid', 
            'rg_partner', 'rg_ofw', 'rg_others',
        ),
        'consultreason' => array(
            'cr_routine', 'cr_sti_services', 'cr_hiv_services', 'cr_others'
        ),
        'gramstain' => array(
            'no_evidence_res', 'gono_res', 'ngi_res'
        ),
        'referral' => array(
            'ref_antenatal', 'ref_tb_dots', 'ref_physician', 'ref_treat_hub', 'ref_others'
        )
    );

    public function normalFill(array $attributes){
        return parent::fill($attributes);
    }

    private function splitString($s){
        $s = explode('_', $s);
        return [implode('_', array_slice($s, 0, -1)), end($s)];
    }

    private function requireIfInArray($source, $target, $values){
        foreach ($source as $s){
            if(in_array($s, $values)){
                $this->rules[$target] = 'required_if:' . $s . ",1";
            }
            return true;
        }
        return false;
    }

    public function __construct(array $attributes = []){
        foreach ($this->tests as $key=>$values){
            foreach ($values as $value){
                $field_type = $this->splitString($value);
                switch ($field_type[1]){
                    case "res":
                        $this->requireIfInArray(array($field_type[0] . '_insp'), $value, $values);
                        break;
                    case "inf":
                        $this->requireIfInArray(array($field_type[0] . '_res'), $value, $values);
                        break;
                    case "prev":
                        $source = [$field_type[0] . '_insp', $field_type[0] . '_res'];
                        $this->requireIfInArray($source, $value, $values);
                        break;
                    case "treat":
                        $source = [$field_type[0] . '_res', $field_type[0] . '_insp'];
                        $this->requireIfInArray($source, $value, $values);
                        break;
                    case "vac":
                        $this->requireIfInArray([$field_type[0] . '_res'], $value, $values);
                        break;
                    default:
                        break;
                }
            }
        }
        parent::__construct($attributes);
    }

    public function fill(array $attributes){
        foreach($this->checkboxes as $checkbox_group => $checkbox_options){
            foreach($checkbox_options as $option){
                if(!array_key_exists($option, $attributes)){
                    $attributes[$option] = 0;
                }
            }
        }

        foreach($this->tests as $test => $fields){
            if(!array_key_exists($test, $attributes)){
                $attributes[$test] = 0;
                foreach($fields as $field){
                    unset($attributes[$field]);
                }
            }
        }

        return parent::fill($attributes);
    }

    private function createKey(){
        $hashstring = strtolower($this->attributes['firstname']);
        if (array_key_exists('middlename', $this->attributes))
            $hashstring .= strtolower($this->attributes['middlename']);
        $hashstring .= strtolower($this->attributes['lastname']);
        $hashstring .= strtolower($this->attributes['uic']);
        $this->attributes['client_id'] = hash(
            'sha256',
            $hashstring
        );
    }

    public function convertDates(){
        if (!empty($this->attributes['consult_date'])){
            $this->attributes['consult_date'] = date('Y-m-d', strtotime($this->attributes['consult_date']));
        }
        if (!empty($this->attributes['birthdate'])){
            $this->attributes['birthdate'] = date('Y-m-d', strtotime($this->attributes['birthdate']));
        }        
    }

    public function save(Array $options=array()){
        $this->convertDates();

        if (!isset($this->attributes['client_id'])){
            $this->createKey();
        }
        if ($this->attributes['is_perm_resident'] == '1'){
            $this->attributes['perm_municipality'] = $this->attributes['municipality'];
            $this->attributes['perm_province'] = $this->attributes['province'];
            $this->attributes['perm_region'] = $this->attributes['region'];
        }

        $hnc = new HealthNetworkClient;
        $clinic = Shclinic::all()->first();
        $hnc->createTransaction($this->attributes['uic'], $clinic->hp_id, '');
        return parent::save($options);
    }

    public function getConsultDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function formConsultDateAttribute($value){
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function getBirthdateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function formBirthdateAttribute($value){
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function newConsult(){
        $new_icr = new Icr;
        $persistent_fields = [
            'uic',
            'client_id',
            'firstname',
            'middlename',
            'lastname',
            'sex',
            'gender_identity',
            'is_resident',
            'is_perm_resident',
            'municipality', 'province', 'region',
            'perm_municipality', 'perm_province', 'perm_region'
            // etc
        ];
        $prev_matches = [
            'syp_scr_prev' => 'syp_scr_res',
            'syp_conf_prev' => 'syp_conf_res',
            'gono_prev' => 'gono_res',
            'ngi_prev' => 'ngi_res',
            'tri_prev' => 'tri_res',
            'gen_warts_prev' => 'gen_warts_res',
            'gen_anal_prev' => 'gen_anal_res',
            'anal_warts_prev' => 'anal_warts_res',
            'herpes_prev' => 'herpes_res',
            'bacvag_prev' => 'bacvag_res',
        ];
        foreach ($persistent_fields as $field){
            if (array_key_exists($field, $this->attributes))
                $new_icr->{$field} = $this->attributes[$field];
        }
        foreach ($prev_matches as $key => $value){
            if (array_key_exists($value, $this->attributes))
                $new_icr->{$key} = $this->attributes[$value];
        }

        if ($this->attributes['consult_date']){
            $date = $this->attributes['consult_date'];
            if (date('Y', strtotime($date)) < date('Y')){
                $new_icr->consulttype = 1;
            } else {
                $new_icr->consulttype = 2;
            }
        }
        return $new_icr;
    }

    public function hasDuplicate(){
        if (!isset($this->attributes['client_id'])){
            $this->createKey();
        }

        $this->convertDates();
        $original = Icr::where('consult_date', $this->attributes['consult_date'])
            ->where('client_id', $this->attributes['client_id'])
            ->where('uic', $this->attributes['uic'])
            ->where('id', '<>', array_key_exists('id', $this->attributes) ? $this->attributes['id'] : '')
            ->first();
        return $original ? $original : null;
    }

    public function validate($data){
        $pass = true;
        if (!$this->errors){
            $this->errors = new MessageBag();
        }
        $year = date('Y');
        $check_year = (strtotime('now') < strtotime('3/31/' . $year)) ? date('Y', strtotime('-1 year')) : $year;
        if (strtotime($this->attributes['consult_date']) < strtotime('1/1/' . $check_year)){
            $this->errors->add('consult_date', 'Invalid Consult Date');
            $pass = false;
        }

        $d = $this->hasDuplicate();
        if ($d){
            $this->errors->add(
                'unique_warning',
                route(
                    'editConsult',
                    [
                        'uic' => $d->uic,
                        'client_id' => $d->client_id,
                        'pk' => $d->id
                    ]
                )
            );
            $pass = false;
        }
        return parent::validate($data) ? $pass : false;
    }

    public static function latestClientRecords(Array $ids=[]){
        $q = Icr::orderBy('consult_date', 'desc');
        if (!empty($ids)){
            $first = array_shift($ids);
            $q = $q->where('client_id', $first);
        }
        foreach($ids as $id){
            $q = $q->orWhere('client_id', $id);
        }
        return $q->get()->unique('client_id');
    }

    public static function filterDuplicates($duplicates){
        return function ($value, $key) use ($duplicates){
            if (in_array($value['client_id'], $duplicates->keyBy('newest_version')->keys())){
                return true;  // is the newest member of a duplicate group
            }
            if (in_array($value['uic'], $duplicates->keyBy('client_id')->keys())){
                return false;  // is an older member of a duplicate group
            }
            return true;
        };
    }

    public static function getAllClients(){
        $records = Icr::select('client_id', 'firstname', 'lastname', 'sex', 'municipality', 'consult_date', 'uic', 'middlename')
            ->orderBy('consult_date', 'desc');

        // return $records;
        $duplicates = Duplicate::select('newest_version', 'client_id')->where('duplicate', true)->get();
        return $records->get()->unique('client_id')
            ->filter(function ($value, $key) use ($duplicates){
                if (in_array($value['client_id'], $duplicates->keyBy('client_id')->keys()->toArray())){
                    if ($duplicates->keyBy('client_id')[$value['client_id']]['newest_version'] == $value['client_id']){
                        // this is the newest member of a duplicate group
                        return true;
                    } else {
                        return false;
                    }
                }
                return true;
            }
        );
    }
}
