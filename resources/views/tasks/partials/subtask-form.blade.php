<form action="{{route('tasks.store')}}" method="POST">
    @csrf
    <input type="hidden" name="parent_id" value="{{$parent_id}}">

        <h2>Feladat hozzáadása</h2>
        <!--A feladat címe -->
        <label for="title">Az alfeladat neve/címe: </label>
        <input type="text" id="title" name="title" value="{{old('title')}}" required>
        <!--A feladat leírása -->
        <label for="description">Leírás: </label>
        <textarea name="description" id="description" rows="10">{{old('description')}}</textarea>
         <!--A feladat határideje -->
        <label for="due_date">Határidő: </label>
        <input type="date" name="due_date_day" id="due_date_day" value="{{old('due_date_day')}}" required>
        <input type="time" name="due_date_time" id="due_date_time" value="{{old('due_date_time')}}" required>
        <!--A feladat státusza -->
        <label for="status">Státusz: </label>
        <select name="status" id="status">
            <option value="" disabled selected>Státusz választás</option>
            @foreach (\App\Enums\TaskStatus::cases() as $statusCases)
                <option value="{{$statusCases->value}}" {{old('status') == $statusCases->value ? 'selected' : ''}}>
                    {{$statusCases->label()}}
                </option>
            @endforeach
        </select>


        <button type="submit" class="btn mt-4">Feladat hozzáadása</button> 
</form>