<div class="ui modal add medium">
    <div class="header">{{ __('admin.Add New Schedule') }}</div>
    <div class="content">
        <form id="add_schedule_form" action="{{ url('schedules/add') }}" class="ui form" method="post" accept-charset="utf-8">
        {{ csrf_field() }}
            <div class="field">
                <label>{{ __('admin.Department') }}</label>
                <select id="department" class="ui search dropdown getid uppercase" name="department">
                    <option value="">{{ __('admin.Select Department') }}</option>
                    @isset($department)
                        <option value="all" >All</option>
                    @foreach ($department as $data)
                        <option value="{{ $data->department }}" >{{ $data->department }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
            <div class="field" >
                <label>{{ __('admin.Employee') }}</label>
                <select id="employee" multiple class="ui search dropdown getid uppercase" name="employee[]">
                    <option value="">{{ __('admin.Select Employee') }}</option>
                   
                </select>
            </div>

            <div class="two fields">
                <div class="field">
                    <label for="">{{ __('admin.Start time') }}</label>
                    <input type="text" placeholder="00:00:00 AM" name="intime" class="jtimepicker" />
                </div>
                <div class="field">
                    <label for="">{{ __('admin.Off time') }}</label>
                    <input type="text" placeholder="00:00:00 PM" name="outime" class="jtimepicker" />
                </div>
            </div>

            <div class="field">
                <label for="">{{ __('admin.From') }}</label>
                <input type="text" placeholder="Date" name="datefrom" id="datefrom" class="airdatepicker" />
            </div>
            <div class="field">
                <label for="">{{ __('admin.To') }}</label>
                <input type="text" placeholder="Date" name="dateto" id="dateto" class="airdatepicker" />
            </div>

            <div class="eight wide field">
                <label for="">{{ __('admin.Total hours') }}</label>
                <input type="number" placeholder="0" name="hours" />
            </div>

           <div class="grouped fields field">
                <label>{{ __('admin.Choose Rest day') }}</label>
                <div class="field">
                <div class="ui checkbox sunday">
                    <input type="checkbox" name="restday[]" value="Sunday">
                    <label>{{ __('admin.Sunday') }}</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Monday">
                    <label>{{ __('admin.Monday') }}</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Tuesday">
                    <label>{{ __('admin.Tuesday') }}</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Wednesday">
                    <label>{{ __('admin.Wednesday') }}</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Thursday">
                    <label>{{ __('admin.Thursday') }}</label>
                </div>
                </div>
                <div class="field">
                <div class="ui checkbox ">
                    <input type="checkbox" name="restday[]" value="Friday">
                    <label>{{ __('admin.Friday') }}</label>
                </div>
                </div>
                <div class="field" style="padding:0">
                <div class="ui checkbox saturday">
                    <input type="checkbox" name="restday[]" value="Saturday">
                    <label>{{ __('admin.Saturday') }}</label>
                </div>
                </div>
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
            <input type="hidden" name="id" value="">
            <button class="ui positive small button" type="submit" name="submit"><i class="ui checkmark icon"></i> {{ __('admin.Save') }}</button>
            <button class="ui grey small button cancel" type="button"><i class="ui times icon"></i> {{ __('admin.Cancel') }}</button>
            <input type="hidden" id="_url" value="{{url('/')}}">
        </div>
        </form>  
</div>
