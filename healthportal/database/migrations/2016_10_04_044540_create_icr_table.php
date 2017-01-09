<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icr', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id', 255);
            $table->integer('shc_id')->unsigned()->default(0);
            $table->date('consult_date');
            $table->string('uic', 15);

            $table->string('clientnumber', 50)->nullable();
            $table->tinyInteger('consulttype')->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('middlename', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->date('birthdate')->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->tinyInteger('gender_identity')->nullable();
            $table->string('image', 255)->nullable();

            $table->boolean('is_resident')->nullable();
            $table->string('municipality', 3)->nullable();
            $table->string('province', 3)->nullable();
            $table->string('region', 3)->nullable();

            $table->boolean('is_perm_resident')->nullable();
            $table->string('perm_municipality', 3)->nullable();
            $table->string('perm_province', 3)->nullable();
            $table->string('perm_region', 3)->nullable();

            $table->string('establishment', 100)->nullable();
            $table->string('est_type', 100)->nullable();

            $table->boolean('rg_rsw')->nullable();
            $table->boolean('rg_nsw')->nullable();
            $table->boolean('rg_fsw')->nullable();
            $table->boolean('rg_cfsw')->nullable();
            $table->boolean('rg_msm')->nullable();
            $table->boolean('rg_tg')->nullable();
            $table->boolean('rg_pwid')->nullable();
            $table->boolean('rg_partner')->nullable();
            $table->boolean('rg_ofw')->nullable();
            $table->boolean('rg_others')->nullable();
            $table->string('rg_others_text', 100)->nullable();

            $table->integer('client_type')->nullable();
            $table->integer('client_ref')->nullable();
            $table->text('client_ref_reason', 50)->nullable();

            $table->boolean('cr_routine')->nullable();
            $table->boolean('cr_sti_services')->nullable();
            $table->boolean('cr_hiv_services')->nullable();
            $table->boolean('cr_others')->nullable();
            $table->string('cr_others_text', 100)->nullable();

            $table->boolean('is_pregnant')->nullable();

            $table->boolean('syp_scr')->nullable();
            $table->boolean('syp_scr_res')->nullable();
            $table->boolean('syp_scr_inf')->nullable();
            $table->boolean('syp_scr_prev')->nullable();
            $table->boolean('syp_scr_prev_cont')->nullable();
            $table->boolean('syp_scr_treat')->nullable();

            $table->boolean('syp_conf')->nullable();
            $table->boolean('syp_conf_res')->nullable();
            $table->boolean('syp_conf_inf')->nullable();
            $table->boolean('syp_conf_prev')->nullable();
            $table->boolean('syp_conf_prev_cont')->nullable();
            $table->boolean('syp_conf_treat')->nullable();

            $table->boolean('gram_stain')->nullable();
            $table->boolean('no_evidence_res')->nullable();
            $table->boolean('gono_res')->nullable();
            $table->boolean('ngi_res')->nullable();
            $table->boolean('gram_stain_inf')->nullable();
            $table->boolean('gono_prev')->nullable();
            $table->boolean('gono_cont')->nullable();
            $table->boolean('gono_treat')->nullable();

            $table->boolean('ngi_prev')->nullable();
            $table->boolean('ngi_cont')->nullable();
            $table->boolean('ngi_treat')->nullable();

            $table->boolean('wet_mount')->nullable();
            $table->boolean('tri_res')->nullable();
            $table->boolean('tri_prev')->nullable();
            $table->boolean('tri_inf')->nullable();
            $table->boolean('tri_cont')->nullable();
            $table->boolean('tri_treat')->nullable();

            $table->boolean('hbsag')->nullable();
            $table->boolean('hepab_res')->nullable();
            $table->boolean('hepab_inf')->nullable();
            $table->boolean('hepab_vac')->nullable();

            $table->boolean('hepac')->nullable();
            $table->boolean('hepac_res')->nullable();
            $table->boolean('hepac_inf')->nullable();

            $table->boolean('inspected')->nullable();
            $table->boolean('gen_warts_insp')->nullable();
            $table->boolean('gen_warts_prev')->nullable();
            $table->boolean('gen_warts_cont')->nullable();
            $table->boolean('gen_warts_res')->nullable();
            $table->boolean('anal_warts_insp')->nullable();
            $table->boolean('anal_warts_prev')->nullable();
            $table->boolean('anal_warts_cont')->nullable();
            $table->boolean('anal_warts_res')->nullable();

            $table->boolean('herpes_insp')->nullable();
            $table->boolean('herpes_cont')->nullable();
            $table->boolean('herpes_prev')->nullable();
            $table->boolean('herpes_res')->nullable();
            $table->boolean('herpes_treat')->nullable();

            $table->boolean('bacvag_insp')->nullable();
            $table->boolean('bacvag_res')->nullable();
            $table->boolean('bacvag_prev')->nullable();
            $table->boolean('bacvag_cont')->nullable();
            $table->boolean('bacvag_treat')->nullable();

            $table->boolean('referral')->nullable();
            $table->boolean('ref_antenatal')->nullable();
            $table->boolean('ref_tb_dots')->nullable();
            $table->boolean('ref_physician')->nullable();
            $table->boolean('ref_treat_hub')->nullable();
            $table->string('ref_treat_hub_text', 3)->nullable();
            $table->boolean('ref_others')->nullable();
            $table->string('ref_others_text', 100)->nullable();

            $table->string('remarks', 255)->nullable();

            $table->boolean('invalid')->default(false);
            $table->string('guid', 25);
            $table->integer('counter_last_update')->default(false);

            $table->timestamps();

            $table->unique(['client_id', 'shc_id', 'consult_date', 'uic']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icr');
    }
}
