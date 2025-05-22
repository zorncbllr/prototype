import { TrashIcon } from "lucide-react";
import { Button } from "./ui/button";
import { useVoterStore } from "@/stores/store";

const ClearButton = () => {
  const { setOpen } = useVoterStore();

  return (
    <Button variant={"outline"} onClick={() => setOpen(true)}>
      Clear <TrashIcon className="text-gray-700" />
    </Button>
  );
};

export default ClearButton;
