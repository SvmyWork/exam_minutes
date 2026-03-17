const gridOptions = {
    rowData: [
        {
            make: "honda", model: `
        <ul style="list-style-type: none; padding: 0; margin: 0; ">
            <li class="text-xs">1. It is the first stage of the software development life cycle.</li>
            <li class="text-xs">2. It involves gathering requirements from stakeholders.</li>
            <li class="text-xs">3. It focuses on creating a detailed design of the software.</li>
            <li class="text-xs">4. It is the final stage before implementation.</li>
        </ul>`, price: 64950, electric: true
        },
        {
            make: "Tesla", model: `
        <ul style="list-style-type: none; padding: 0; margin: 0; width: 100%;">
            <li class="text-xs">1. It is the first stage of the software development life cycle.</li>
            <li class="text-xs">2. It involves gathering requirements from stakeholders.</li>
            <li class="text-xs">3. It focuses on creating a detailed design of the software.</li>
            <li class="text-xs">4. It is the final stage before implementation.</li>
        </ul>`, price: 64950, electric: true
        },
        {
            make: "Tata", model: `
        <ul style="list-style-type: none; padding: 0; margin: 0; width: 100%;">
            <li class="text-xs">1. It is the first stage of the software development life cycle.</li>
            <li class="text-xs">2. It involves gathering requirements from stakeholders.</li>
            <li class="text-xs">3. It focuses on creating a detailed design of the software.</li>
            <li class="text-xs">4. It is the final stage before implementation.</li>
        </ul>`, price: 64950, electric: true
        },
    ],
    columnDefs: [
        { field: "make" },
        {
            field: "model",
            cellRenderer: params => {
                return `
        <div style="max-height: 50px; overflow-y: auto; overflow-x: hidden; ">
            ${params.value}
        </div>
    `;
            }
        },
        { field: "price" },
        { field: "electric" }
    ],
    getRowHeight: params => {

        return 50; // Default row height
    },
    defaultColDef: {
        flex: 1,
        minWidth: 100,
        resizable: true,
        cellStyle: { fontSize: '14px' },
        cellClass: 'text-xs'
    },
    onGridReady: params => {
        params.api.sizeColumnsToFit();
    },
};

const myGridElement = document.querySelector('#myGrid');
agGrid.createGrid(myGridElement, gridOptions);