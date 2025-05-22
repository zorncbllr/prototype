import { useExportFile } from "@/api/mutations";
import { Button } from "./ui/button";

const ExportButton = () => {
  const { mutate: exportFile } = useExportFile();

  return <Button onClick={() => exportFile()}>Export</Button>;
};

export default ExportButton;
