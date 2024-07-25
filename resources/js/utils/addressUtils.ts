export function isValidPostCode(str: string | undefined): boolean {
  str = str?.replace(/\s/g, "");
  return /^(([A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2})|([A-Z]{1,2}[0-9][A-Z] ?[0-9][A-Z]{2,3}))$/i.test(str ?? "");
}
export function getFormattedAddressLines(lines: string[]): string {
  return lines
    .filter(chunk => chunk.trim() !== '')
    .join(', ');
}