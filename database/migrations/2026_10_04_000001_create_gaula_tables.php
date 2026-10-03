<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Generert fra Django-modellene (gaula-python). Tabell- og kolonnenavnene er de samme,
// så dataene kan flyttes 1:1. Sjekk-regler (CheckConstraint) ligger i modellene.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stations_station', function (Blueprint $table) {
            $table->string('nve_id', 20)->primary();
            $table->string('name', 100)->default('');
            $table->string('place', 100)->default('');
            $table->string('municipality_id', 10)->default('');
            $table->json('parameter_codes')->nullable();
            $table->double('lat');
            $table->double('lon');
        });

        Schema::create('stations_parameter', function (Blueprint $table) {
            $table->integer('code')->primary();
            $table->string('label', 50)->default('');
            $table->string('unit', 20)->default('');
            $table->string('color', 30)->default('');
        });

        Schema::create('stations_observation', function (Blueprint $table) {
            $table->id();
            $table->string('station_id', 20);
            $table->integer('parameter_id');
            $table->dateTime('time');
            $table->double('value')->nullable();
            $table->integer('resolution_time')->default(60);
            $table->dateTime('fetched_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('station_id')->references('nve_id')->on('stations_station')->cascadeOnDelete();
            $table->foreign('parameter_id')->references('code')->on('stations_parameter')->cascadeOnDelete();
            $table->unique(['station_id', 'parameter_id', 'time', 'resolution_time'], 'unique_observation');
            $table->index(['station_id', 'parameter_id', 'resolution_time', 'time'], 'stations_ob_station_58364f_idx');
        });

        Schema::create('stations_seasonalnormal', function (Blueprint $table) {
            $table->id();
            $table->string('station_id', 20);
            $table->integer('parameter_id');
            $table->integer('day_of_year');
            $table->double('median');
            $table->double('p25');
            $table->double('p75');
            $table->integer('sample_size')->default(0);
            $table->integer('window_days')->default(7);
            $table->integer('lookback_years')->default(30);
            $table->dateTime('computed_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('station_id')->references('nve_id')->on('stations_station')->cascadeOnDelete();
            $table->foreign('parameter_id')->references('code')->on('stations_parameter')->cascadeOnDelete();
            $table->unique(['station_id', 'parameter_id', 'day_of_year'], 'unique_seasonal_normal');
        });

        Schema::create('catches_beat', function (Blueprint $table) {
            $table->integer('elveguiden_id')->primary();
            $table->string('name', 200)->default('');
        });

        Schema::create('catches_salmoncatch', function (Blueprint $table) {
            $table->bigInteger('elveguiden_id')->primary();
            $table->dateTime('timestamp');
            $table->string('species', 50)->default('');
            $table->double('weight_kg');
            $table->double('length_cm');
            $table->boolean('released');
            $table->string('gear', 100)->default('');
            $table->integer('beat_id');
            $table->string('fisher_name', 200)->nullable();
            $table->foreign('beat_id')->references('elveguiden_id')->on('catches_beat')->cascadeOnDelete();
            $table->index(['timestamp'], 'catches_sal_timesta_2f88b4_idx');
        });

        Schema::create('catches_seasonsync', function (Blueprint $table) {
            $table->integer('year')->primary();
            $table->dateTime('synced_at');
        });

        Schema::create('nina_ninaweeklycount', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20)->default('');
            $table->integer('year');
            $table->integer('period_index');
            $table->string('period_label', 20)->default('');
            $table->integer('count');
            $table->date('snapshot_date');
            $table->unique(['category', 'year', 'period_index'], 'unique_weekly_count');
        });

        Schema::create('nina_ninacumulativecount', function (Blueprint $table) {
            $table->id();
            $table->string('category', 20)->default('');
            $table->integer('year');
            $table->integer('period_index');
            $table->string('period_label', 20)->default('');
            $table->integer('cumulative_count');
            $table->date('snapshot_date');
            $table->unique(['category', 'year', 'period_index'], 'unique_cumulative_count');
        });

        Schema::create('vald_valdinfo', function (Blueprint $table) {
            $table->integer('elveguiden_id')->primary();
            $table->string('slug', 100)->unique();
            $table->string('name', 200)->default('');
            $table->string('place', 200)->default('');
            $table->boolean('confirmed')->default(true);
            $table->json('map')->nullable();
        });

        Schema::create('vald_facility', function (Blueprint $table) {
            $table->id();
            $table->integer('vald_id');
            $table->string('description', 200)->default('');
            $table->foreign('vald_id')->references('elveguiden_id')->on('vald_valdinfo')->cascadeOnDelete();
        });

        Schema::create('vald_valdphoto', function (Blueprint $table) {
            $table->id();
            $table->integer('vald_id');
            $table->string('image', 100)->default('');
            $table->string('caption', 200)->default('');
            $table->foreign('vald_id')->references('elveguiden_id')->on('vald_valdinfo')->cascadeOnDelete();
        });

        Schema::create('vald_pool', function (Blueprint $table) {
            $table->id();
            $table->integer('vald_id');
            $table->string('name', 100)->default('');
            $table->string('name_en', 100)->default('');
            $table->string('name_de', 100)->default('');
            $table->string('short_name', 40)->default('');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('in_rotation')->default(true);
            $table->text('description')->nullable();
            $table->json('map')->nullable();
            $table->foreign('vald_id')->references('elveguiden_id')->on('vald_valdinfo')->restrictOnDelete();
        });

        Schema::create('vald_mappoint', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20)->default('parking');
            $table->string('name', 100)->default('');
            $table->integer('vald_id')->nullable();
            $table->json('map')->nullable();
            $table->foreign('vald_id')->references('elveguiden_id')->on('vald_valdinfo')->cascadeOnDelete();
        });

        Schema::create('access_accesscode', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('label', 200)->default('');
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('last_used_at')->nullable();
            $table->unsignedInteger('use_count')->default(0);
        });

        Schema::create('lodging_room', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->default('');
            $table->unsignedSmallInteger('beds')->default(2);
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('week_price')->nullable();
        });

        Schema::create('lodging_roomprice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('label', 60)->default('');
            $table->date('start');
            $table->date('end');
            $table->unsignedInteger('week_price');
            $table->foreign('room_id')->references('id')->on('lodging_room')->cascadeOnDelete();
        });

        Schema::create('crm_guest', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->default('');
            $table->string('phone', 40)->default('');
            $table->string('email', 254)->default('');
            $table->string('language', 2)->default('no');
            $table->text('notes')->nullable();
            $table->text('preferences')->nullable();
            $table->dateTime('anonymized_at')->nullable();
            $table->string('pin_hash', 128)->default('');
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('lodging_stay', function (Blueprint $table) {
            $table->id();
            $table->string('label', 200)->default('');
            $table->date('arrival');
            $table->date('departure');
            $table->unsignedSmallInteger('fishers')->default(2);
            $table->unsignedSmallInteger('rods')->nullable();
            $table->string('status', 20)->default('confirmed');
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('contact_name', 200)->default('');
            $table->string('contact_phone', 40)->default('');
            $table->string('contact_email', 254)->default('');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('access_code_id')->nullable();
            $table->boolean('auto_code')->default(true);
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('fishing_price')->nullable();
            $table->boolean('deposit_required')->default(false);
            $table->unsignedInteger('deposit')->default(0);
            $table->date('deposit_due')->nullable();
            $table->date('payment_due')->nullable();
            $table->string('payment_status', 20)->default('none');
            $table->string('payment_note', 200)->default('');
            $table->dateTime('created_at')->useCurrent();
            $table->string('reference', 20)->default('')->index();
            $table->unsignedBigInteger('shares_with_id')->nullable();
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('checked_out_at')->nullable();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->nullOnDelete();
            $table->foreign('access_code_id')->references('id')->on('access_accesscode')->nullOnDelete();
            $table->foreign('shares_with_id')->references('id')->on('lodging_stay')->nullOnDelete();
        });

        Schema::create('lodging_draw', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->unique();
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('lodging_drawnfisher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('draw_id');
            $table->unsignedSmallInteger('number');
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('name', 100)->default('');
            $table->unsignedBigInteger('stay_id')->nullable();
            $table->foreign('draw_id')->references('id')->on('lodging_draw')->cascadeOnDelete();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->nullOnDelete();
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->nullOnDelete();
            $table->unique(['draw_id', 'number'], 'drawnfisher_unique_number');
        });

        Schema::create('lodging_staynote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stay_id');
            $table->text('text')->nullable();
            $table->string('created_by', 150)->default('');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->cascadeOnDelete();
        });

        Schema::create('lodging_tasktemplate', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->default('');
            $table->string('kind', 10)->default('week');
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('lodging_weektask', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->index();
            $table->string('title', 200)->default('');
            $table->unsignedBigInteger('stay_id')->nullable();
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->string('done_by', 150)->default('');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->cascadeOnDelete();
            $table->foreign('assigned_to_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('lodging_roomcheckitem', function (Blueprint $table) {
            $table->id();
            $table->string('title', 60)->default('');
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('lodging_roomcheck', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->index();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('item_id');
            $table->dateTime('done_at');
            $table->string('done_by', 150)->default('');
            $table->foreign('room_id')->references('id')->on('lodging_room')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('lodging_roomcheckitem')->cascadeOnDelete();
            $table->unique(['week_start', 'room_id', 'item_id'], 'roomcheck_unique');
        });

        Schema::create('lodging_chore', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->default('');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('priority', 10)->default('normal');
            $table->date('due')->nullable();
            $table->unsignedSmallInteger('season')->nullable();
            $table->unsignedBigInteger('assigned_to_id')->nullable();
            $table->dateTime('done_at')->nullable();
            $table->string('done_by', 150)->default('');
            $table->string('created_by', 150)->default('');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('room_id')->references('id')->on('lodging_room')->nullOnDelete();
            $table->foreign('assigned_to_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('lodging_calendarfeed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('token', 64)->unique();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('lodging_democlock', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fake_now')->nullable();
        });

        Schema::create('lodging_sitesettings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('rods_per_week')->default(8);
            $table->boolean('rotation_follows_rods')->default(true);
            $table->unsignedSmallInteger('rod_day_markup')->default(15);
            $table->unsignedInteger('rod_price')->nullable();
            $table->boolean('feat_messages')->default(true);
            $table->boolean('feat_chores')->default(true);
            $table->boolean('feat_room_checks')->default(true);
            $table->boolean('feat_week_tasks')->default(true);
            $table->boolean('feat_deposit')->default(true);
            $table->boolean('feat_companions')->default(true);
            $table->boolean('feat_rods')->default(true);
            $table->boolean('feat_fishing_price')->default(true);
            $table->boolean('day_prices')->default(false);
            $table->boolean('feat_calendar')->default(false);
            $table->boolean('feat_season_wizard')->default(true);
            $table->boolean('before_arrival')->default(false);
            $table->boolean('river_conditions')->default(true);
            $table->boolean('report_catch')->default(false);
            $table->boolean('ask_feedback')->default(true);
            $table->boolean('menu_gjenkjop')->default(true);
            $table->boolean('theme_picker')->default(false);
            $table->unsignedInteger('flow_low')->default(40);
            $table->unsignedInteger('flow_flood')->default(180);
            $table->decimal('temp_warm', 4, 1)->default(18);
            $table->boolean('show_booking_ref')->default(false);
        });

        Schema::create('lodging_weekcapacity', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->unique();
            $table->unsignedSmallInteger('rods');
        });

        Schema::create('lodging_rodprice', function (Blueprint $table) {
            $table->id();
            $table->string('label', 60)->default('');
            $table->date('start');
            $table->date('end');
            $table->unsignedInteger('price');
        });

        Schema::create('lodging_dayprice', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 10)->default('');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('label', 60)->default('');
            $table->date('start');
            $table->date('end');
            $table->unsignedInteger('price');
            $table->foreign('room_id')->references('id')->on('lodging_room')->cascadeOnDelete();
        });

        Schema::create('guide_infosection', function (Blueprint $table) {
            $table->id();
            $table->string('title_no', 200)->default('');
            $table->string('title_en', 200)->default('');
            $table->string('title_de', 200)->default('');
            $table->text('body_no')->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_de')->nullable();
            $table->string('image', 100)->default('');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_visible')->default(true);
        });

        Schema::create('guide_quicklink', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('');
            $table->string('url', 200)->default('');
            $table->string('icon', 40)->default('link');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_visible')->default(true);
        });

        Schema::create('guide_announcement', function (Blueprint $table) {
            $table->id();
            $table->string('level', 10)->default('info');
            $table->string('text_no', 300)->default('');
            $table->string('text_en', 300)->default('');
            $table->string('text_de', 300)->default('');
            $table->string('link', 200)->default('');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('guide_arrivalitem', function (Blueprint $table) {
            $table->id();
            $table->string('text_no', 200)->default('');
            $table->string('text_en', 200)->default('');
            $table->string('text_de', 200)->default('');
            $table->string('link', 200)->default('');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
        });

        Schema::create('guide_arrivalcheck', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('stay_id');
            $table->dateTime('done_at');
            $table->foreign('item_id')->references('id')->on('guide_arrivalitem')->cascadeOnDelete();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->cascadeOnDelete();
            $table->unique(['item_id', 'guest_id', 'stay_id'], 'arrivalcheck_unique');
        });

        Schema::create('crm_guesttag', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
        });

        Schema::create('crm_guestcatchalias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->string('fisher_name', 200)->unique();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
        });

        Schema::create('crm_emailtemplate', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 20)->default('');
            $table->string('language', 2)->default('');
            $table->string('subject', 200)->default('');
            $table->text('body')->nullable();
            $table->unique(['kind', 'language'], 'emailtemplate_unique');
        });

        Schema::create('crm_sentmessage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stay_id')->nullable();
            $table->unsignedBigInteger('guest_id')->nullable();
            $table->string('kind', 20)->default('');
            $table->string('to', 254)->default('');
            $table->string('subject', 200)->default('');
            $table->text('body')->nullable();
            $table->dateTime('sent_at')->useCurrent();
            $table->string('sent_by', 150)->default('');
            $table->string('via', 10)->default('manual');
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->nullOnDelete();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->nullOnDelete();
        });

        Schema::create('crm_messagethread', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->string('subject', 120)->default('');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
        });

        Schema::create('crm_guestmessage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thread_id');
            $table->unsignedBigInteger('guest_id');
            $table->boolean('from_guest')->default(true);
            $table->text('body')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('read_at')->nullable();
            $table->string('sent_by', 150)->default('');
            $table->string('sender_name', 100)->default('');
            $table->dateTime('deleted_at')->nullable();
            $table->boolean('hidden_for_guest')->default(false);
            $table->boolean('hidden_for_admin')->default(false);
            $table->foreign('thread_id')->references('id')->on('crm_messagethread')->cascadeOnDelete();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
            $table->index(['from_guest', 'read_at'], 'crm_melding_ulest_idx');
        });

        Schema::create('crm_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('stay_id')->nullable();
            $table->text('text')->nullable();
            $table->boolean('declined')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->nullOnDelete();
        });

        Schema::create('stats_weeksnapshot', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->unique();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedSmallInteger('week_number');
            $table->unsignedSmallInteger('rooms_total')->default(0);
            $table->unsignedInteger('room_nights_available')->default(0);
            $table->unsignedInteger('room_nights_sold')->default(0);
            $table->unsignedSmallInteger('stays_confirmed')->default(0);
            $table->unsignedSmallInteger('stays_requested')->default(0);
            $table->unsignedSmallInteger('stays_waitlist')->default(0);
            $table->unsignedSmallInteger('fishers')->default(0);
            $table->unsignedInteger('rod_days')->default(0);
            $table->unsignedSmallInteger('guests_new')->default(0);
            $table->unsignedSmallInteger('guests_returning')->default(0);
            $table->unsignedInteger('revenue_agreed')->default(0);
            $table->unsignedInteger('catches_ours')->default(0);
            $table->double('kg_ours')->default(0);
            $table->unsignedInteger('catches_river')->default(0);
            $table->double('flow_avg')->nullable();
            $table->double('temp_avg')->nullable();
            $table->unsignedInteger('messages_in')->default(0);
            $table->unsignedInteger('response_minutes')->nullable();
            $table->boolean('is_final')->default(false);
            $table->dateTime('taken_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('stats_externalfigure', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedSmallInteger('month')->nullable();
            $table->string('category', 10)->default('');
            $table->string('label', 100)->default('');
            $table->integer('amount');
            $table->string('note', 200)->default('');
            $table->dateTime('created_at')->useCurrent();
        });

        Schema::create('crm_guest_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('guesttag_id');
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
            $table->foreign('guesttag_id')->references('id')->on('crm_guesttag')->cascadeOnDelete();
            $table->unique(['guest_id', 'guesttag_id'], 'crm_guest_tags_uniq');
        });

        Schema::create('lodging_stay_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stay_id');
            $table->unsignedBigInteger('room_id');
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->cascadeOnDelete();
            $table->foreign('room_id')->references('id')->on('lodging_room')->cascadeOnDelete();
            $table->unique(['stay_id', 'room_id'], 'lodging_stay_rooms_uniq');
        });

        Schema::create('lodging_stay_companions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stay_id');
            $table->unsignedBigInteger('guest_id');
            $table->foreign('stay_id')->references('id')->on('lodging_stay')->cascadeOnDelete();
            $table->foreign('guest_id')->references('id')->on('crm_guest')->cascadeOnDelete();
            $table->unique(['stay_id', 'guest_id'], 'lodging_stay_companions_uniq');
        });

    }

    public function down(): void
    {
        foreach ([
            'lodging_stay_companions',
            'lodging_stay_rooms',
            'crm_guest_tags',
            'stats_externalfigure',
            'stats_weeksnapshot',
            'crm_feedback',
            'crm_guestmessage',
            'crm_messagethread',
            'crm_sentmessage',
            'crm_emailtemplate',
            'crm_guestcatchalias',
            'crm_guesttag',
            'guide_arrivalcheck',
            'guide_arrivalitem',
            'guide_announcement',
            'guide_quicklink',
            'guide_infosection',
            'lodging_dayprice',
            'lodging_rodprice',
            'lodging_weekcapacity',
            'lodging_sitesettings',
            'lodging_democlock',
            'lodging_calendarfeed',
            'lodging_chore',
            'lodging_roomcheck',
            'lodging_roomcheckitem',
            'lodging_weektask',
            'lodging_tasktemplate',
            'lodging_staynote',
            'lodging_drawnfisher',
            'lodging_draw',
            'lodging_stay',
            'crm_guest',
            'lodging_roomprice',
            'lodging_room',
            'access_accesscode',
            'vald_mappoint',
            'vald_pool',
            'vald_valdphoto',
            'vald_facility',
            'vald_valdinfo',
            'nina_ninacumulativecount',
            'nina_ninaweeklycount',
            'catches_seasonsync',
            'catches_salmoncatch',
            'catches_beat',
            'stations_seasonalnormal',
            'stations_observation',
            'stations_parameter',
            'stations_station',
        ] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
