<div class="ui modal small edit">
    <div class="header">{{__('admin.Edit Role')}}</div>
    <div class="content">
    <form id="edit_role_form" action="{{ url('users/roles/update') }}" class="ui form" method="post" accept-charset="utf-8">
    {{ csrf_field() }}
        <div class="field">
            <label>{{__('admin.Role Name')}}</label>
            <input class="uppercase" name="role_name" value="" type="text">
        </div>
        <div class="field">
            <label>Status</label>
            <select name="state" class="ui dropdown state uppercase">
                <option value="Active">{{__('admin.Active')}}</option>
                <option value="Disabled">{{__('admin.Disabled')}}</option>
            </select>
        </div>
        <div class="field">
            <div class="ui error message">
                <i class="close icon"></i>
                <div class="header"></div>
                <ul class="list">
                    <li class=""></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="actions">
        <input type="hidden" value="" name="id" class="" readonly="">
        <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{__('admin.Update')}}</button>
        <button class="ui grey cancel small button" type="button"><i class="ui times icon"></i> {{__('admin.Cancel')}}</button>
    </div>
    </form>  
</div>
