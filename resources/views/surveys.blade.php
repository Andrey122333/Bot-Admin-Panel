@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.admin-user.actions.create'))

@section('body')
@livewire("surveys-blade")

@livewireScripts
@endsection