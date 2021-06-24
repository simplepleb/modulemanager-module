<div class="row">
    @foreach($settings as $key => $value)
        @if( is_array($value))
            @foreach( $value as $key_a => $value_a)
            <div class="col-5">
                <div class="form-group">
                    <?php
                    $field_name = $key_a;
                    $field_lable = label_case($key_a);
                    $field_placeholder = $field_lable;
                    $required = "";
                    $disabled = null;
                    $disabled = "";
                    if( $key_a === 'name' || $key_a === 'slug'){
                        $disabled = "disabled";
                    }
                    $select_options = [
                        '1'=>'True',
                        '0'=>'False',
                    ];


                    ?>
                    {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
                        @if($value_a === true || $value_a === false  )
                            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"])->value($value_a) }}
                        @else
                            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
                        @endif
                </div>
            </div>
            @endforeach
        @else
    <div class="col-5">
        <div class="form-group">
            <?php
            $field_name = $key;
            $field_lable = label_case($key);
            $field_placeholder = $field_lable;
            $required = "";
            $disabled = "";
            if( $key === 'name' || $key === 'slug'){
                $disabled = "disabled";
            }


            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required","$disabled"]) }}
        </div>
    </div>
        @endif
    @endforeach



</div>

<div></div>




@push('after-styles')

@endpush

@push ('after-scripts')




@endpush
