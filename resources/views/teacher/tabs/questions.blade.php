<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });
        // $('#example').DataTable();
    </script>
</head>

<table id="example" class="display">
        <thead>
            <tr>
                <th>No.</th>
                <th>Questions</th>
                <th>Answers</th>
                <th>Options</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>What is the capital of France?</td>
                <td>Edinburgh</td>
                <td>a) abc b) cde</td>
                <td>1</td>
                <td>Edit/Delete</td>
            </tr>
            <tr>
                <td>2</td>
                <td>What is the largest planet in our solar system?</td>
                <td>Tokyo</td>
                <td>a) abc b) cde</td>
                <td>1</td>
                <td>Edit/Delete</td>
            </tr>
            <tr>
                <td>3</td>
                <td>What is the chemical symbol for water?</td>
                <td>Tokyo</td>
                <td>a) abc b) cde</td>
                <td>1</td>
                <td>Edit/Delete</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Questions</th>
                <th>Answers</th>
                <th>Options</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>