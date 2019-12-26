@extends('app')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <b>Image Analysis</b>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label for="image" class="col-4 col-form-label">Image upload</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-image"></i>
                                        </div>
                                    </div>
                                    <input type="file" name="image" accept="image/*" placeholder="JPEG/GIF/PNG" aria-describedby="imageHelpBlock" required="required" class="form-control">
                                </div>
                                <span id="imageHelpBlock" class="form-text text-muted">Select an image you wish to begin analysis.</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-4 col-8">
                                <button name="submit" type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->

@endsection
