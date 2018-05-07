@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-3 ">
        {{$data->name}}
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @foreach($data->modules as $index=>$module)
                <div class="panel">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse{{$module['id']}}" aria-controls="collapse{{$module['id']}}">
                                {{$module['name']}}
                            </a>
                        </h4>
                    </div>
                    <div id="collapse{{$module['id']}}" class="panel-collapse collapse {{$index==0 ? 'in':''}}"
                         role="tabpanel" aria-labelledby="headingOne">
                        @foreach($module['apis'] as $api)
                            <div class="panel-body">
                                <span style="cursor: pointer;" onclick="viewDoc({{$api->markdown}})">{{$api['name']}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach


        </div>
    </div>
   <div class="col-md-8 markdown-body" id="docContent"></div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<link href="https://cdn.bootcss.com/github-markdown-css/2.10.0/github-markdown.css" rel="stylesheet">
<script>

    function viewDoc (doc) {
    if(doc){
      document.getElementById('docContent').innerHTML= marked(doc.content);
    }
    else{
      document.getElementById('docContent').innerHTML= marked('## 暂时没有文档\n\n >让你们后端快点写');
    }
    }
</script>
@endsection



