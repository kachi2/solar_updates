@extends('layouts.admin')
@section('content')
<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-title">Category</h6>
                                <div>
                                    <a href="#" class="mr-3">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </a>
                                       
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="table-responsive">
                                        <table id="myTable" class="table table-striped table-bordered">
                                           <thead>
                                            <tr><th class="text-left">S/N</th>
                                                <th>Category</th>
                                                <th>Sub Categories</th>
                                                <th>Image</th>
                                                 <th>Created At</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                  
                                        @if(count($category) > 0)
                                        @foreach ($category as  $sp)
                                            <tr>
                                            <td>{{$sp->id}}</td>
                                                <td>
                                                    <a href="#">{{$sp->name}}</a>
                                                </td> 
                                               
                                                <td>
                                                    @foreach ($sp->subCategories as $item)
                                                    <i class="fa fa-dot-circle-o"> </i> <a href="#">{{$item->name}}</a> <br>
                                                    @endforeach
                                                </td> 
                                              
                                            
                                                <td>
                                                    <a href="#"><img src="{{asset('/images/category/'.$sp->image)}}" width="50px" height="50px"></a> 
                                                </td>        
                                                  <td>
                                                    <a href="#">{{$sp->created_at->format('d/M/y')}}</a>
                                                </td>
                                                         @php
                                                        $id = $sp->id;
                                                        $parameter = encrypt($id);
                                                        @endphp
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="{{route('category.edit', encrypt($sp->id))}}" class="dropdown-item">Edit Cateogry</a>
                                                            <a href="{{route('edit.subcat', encrypt($sp->id))}}" class="dropdown-item">Edit Sub Cateogry</a>
                                                            <a href="{{route('create.subcat', encrypt($sp->id))}}" class="dropdown-item">Add Sub Category</a>  
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                              @endforeach
                                              @else 
                                              <tr>
                                              <td> No data available </td>
                                              </tr>
                                              @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                 </div>
                  </div>

@endsection