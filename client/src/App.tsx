import type { ColumnDef } from "@tanstack/react-table";
import { DataTable } from "./components/data-table";
import type { Voter } from "./types/types";
import { useFetchVoters } from "./api/queries";
import { Checkbox } from "./components/ui/checkbox";
import ImportButton from "./components/import-button";
import { useChangeStatus } from "./api/mutations";
import ExportButton from "./components/export-button";
import ClearButton from "./components/clear-button";
import DeleteModal from "./components/delete-modal";

export const columns: ColumnDef<Voter>[] = [
  {
    id: "select",
    header: ({ table }) => (
      <Checkbox
        checked={table.getIsAllPageRowsSelected()}
        onCheckedChange={(value) => table.toggleAllPageRowsSelected(!!value)}
        aria-label="Select all"
      />
    ),
    cell: ({ row }) => {
      const raw = row.original;
      const { mutate } = useChangeStatus();
      row.toggleSelected(raw.isGiven);

      return (
        <Checkbox
          checked={row.getIsSelected()}
          onCheckedChange={(value) => {
            row.toggleSelected(!!value);
            console.log(!raw.isGiven);
            mutate({ voterId: raw.voterId, value: !!value });
          }}
          aria-label="Select row"
        />
      );
    },
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: "voterId",
    header: "Voter ID",
  },
  {
    accessorKey: "name",
    header: "Name",
  },
  {
    accessorKey: "precinct",
    header: "Precinct",
  },
];

function App() {
  const { data: voters } = useFetchVoters();

  return (
    <div className="flex justify-center pt-12">
      <div className="w-4/5">
        {voters && (
          <DataTable
            filter="name"
            data={voters}
            columns={columns}
            actions={[<ExportButton />, <ImportButton />, <ClearButton />]}
          />
        )}
      </div>

      <DeleteModal />
    </div>
  );
}

export default App;
