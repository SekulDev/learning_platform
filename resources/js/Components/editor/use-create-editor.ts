import { withProps } from "@udecode/cn";
import {
    ParagraphPlugin,
    PlateLeaf,
    usePlateEditor,
} from "@udecode/plate/react";
import { AIPlugin } from "@udecode/plate-ai/react";
import {
    BoldPlugin,
    CodePlugin,
    ItalicPlugin,
    StrikethroughPlugin,
    SubscriptPlugin,
    SuperscriptPlugin,
    UnderlinePlugin,
} from "@udecode/plate-basic-marks/react";
import { BlockquotePlugin } from "@udecode/plate-block-quote/react";
import {
    CodeBlockPlugin,
    CodeLinePlugin,
    CodeSyntaxPlugin,
} from "@udecode/plate-code-block/react";
import { DatePlugin } from "@udecode/plate-date/react";
import { EmojiInputPlugin } from "@udecode/plate-emoji/react";
import { HEADING_KEYS } from "@udecode/plate-heading";
import { TocPlugin } from "@udecode/plate-heading/react";
import { HorizontalRulePlugin } from "@udecode/plate-horizontal-rule/react";
import { KbdPlugin } from "@udecode/plate-kbd/react";
import { LinkPlugin } from "@udecode/plate-link/react";
import {
    EquationPlugin,
    InlineEquationPlugin,
} from "@udecode/plate-math/react";
import {
    FilePlugin,
    ImagePlugin,
    MediaEmbedPlugin,
    PlaceholderPlugin,
} from "@udecode/plate-media/react";
import { SlashInputPlugin } from "@udecode/plate-slash-command/react";
import {
    TableCellHeaderPlugin,
    TableCellPlugin,
    TablePlugin,
    TableRowPlugin,
} from "@udecode/plate-table/react";
import { TogglePlugin } from "@udecode/plate-toggle/react";
import { editorPlugins } from "@/Components/editor/plugins/editor-plugins";
import { FixedToolbarPlugin } from "@/Components/editor/plugins/fixed-toolbar-plugin";
import { FloatingToolbarPlugin } from "@/Components/editor/plugins/floating-toolbar-plugin";
import { AILeaf } from "@/Components/plate-ui/ai-leaf";
import { BlockquoteElement } from "@/Components/plate-ui/blockquote-element";
import { CodeBlockElement } from "@/Components/plate-ui/code-block-element";
import { CodeLeaf } from "@/Components/plate-ui/code-leaf";
import { CodeLineElement } from "@/Components/plate-ui/code-line-element";
import { CodeSyntaxLeaf } from "@/Components/plate-ui/code-syntax-leaf";
import { DateElement } from "@/Components/plate-ui/date-element";
import { EmojiInputElement } from "@/Components/plate-ui/emoji-input-element";
import { EquationElement } from "@/Components/plate-ui/equation-element";
import { HeadingElement } from "@/Components/plate-ui/heading-element";
import { HrElement } from "@/Components/plate-ui/hr-element";
import { ImageElement } from "@/Components/plate-ui/image-element";
import { InlineEquationElement } from "@/Components/plate-ui/inline-equation-element";
import { KbdLeaf } from "@/Components/plate-ui/kbd-leaf";
import { LinkElement } from "@/Components/plate-ui/link-element";
import { MediaEmbedElement } from "@/Components/plate-ui/media-embed-element";
import { ParagraphElement } from "@/Components/plate-ui/paragraph-element";
import { withPlaceholders } from "@/Components/plate-ui/placeholder";
import { SlashInputElement } from "@/Components/plate-ui/slash-input-element";
import {
    TableCellElement,
    TableCellHeaderElement,
} from "@/Components/plate-ui/table-cell-element";
import { TableElement } from "@/Components/plate-ui/table-element";
import { TableRowElement } from "@/Components/plate-ui/table-row-element";
import { TocElement } from "@/Components/plate-ui/toc-element";
import { ToggleElement } from "@/Components/plate-ui/toggle-element";
import { Value } from "@udecode/plate";
import { MediaFileElement } from "@/Components/plate-ui/media-file-element";
import { MediaPlaceholderElement } from "@/Components/plate-ui/media-placeholder-element";

export const useCreateEditor = (value: Value) => {
    return usePlateEditor({
        override: {
            components: withPlaceholders({
                [AIPlugin.key]: AILeaf,
                [BlockquotePlugin.key]: BlockquoteElement,
                [BoldPlugin.key]: withProps(PlateLeaf, { as: "strong" }),
                [CodeBlockPlugin.key]: CodeBlockElement,
                [CodeLinePlugin.key]: CodeLineElement,
                [CodePlugin.key]: CodeLeaf,
                [CodeSyntaxPlugin.key]: CodeSyntaxLeaf,
                [DatePlugin.key]: DateElement,
                [EmojiInputPlugin.key]: EmojiInputElement,
                [EquationPlugin.key]: EquationElement,
                [FilePlugin.key]: MediaFileElement,
                [HEADING_KEYS.h1]: withProps(HeadingElement, { variant: "h1" }),
                [HEADING_KEYS.h2]: withProps(HeadingElement, { variant: "h2" }),
                [HEADING_KEYS.h3]: withProps(HeadingElement, { variant: "h3" }),
                [HEADING_KEYS.h4]: withProps(HeadingElement, { variant: "h4" }),
                [HEADING_KEYS.h5]: withProps(HeadingElement, { variant: "h5" }),
                [HEADING_KEYS.h6]: withProps(HeadingElement, { variant: "h6" }),
                [HorizontalRulePlugin.key]: HrElement,
                [ImagePlugin.key]: ImageElement,
                [InlineEquationPlugin.key]: InlineEquationElement,
                [ItalicPlugin.key]: withProps(PlateLeaf, { as: "em" }),
                [KbdPlugin.key]: KbdLeaf,
                [LinkPlugin.key]: LinkElement,
                [MediaEmbedPlugin.key]: MediaEmbedElement,
                [ParagraphPlugin.key]: ParagraphElement,
                [PlaceholderPlugin.key]: MediaPlaceholderElement,
                [SlashInputPlugin.key]: SlashInputElement,
                [StrikethroughPlugin.key]: withProps(PlateLeaf, { as: "s" }),
                [SubscriptPlugin.key]: withProps(PlateLeaf, { as: "sub" }),
                [SuperscriptPlugin.key]: withProps(PlateLeaf, { as: "sup" }),
                [TableCellHeaderPlugin.key]: TableCellHeaderElement,
                [TableCellPlugin.key]: TableCellElement,
                [TablePlugin.key]: TableElement,
                [TableRowPlugin.key]: TableRowElement,
                [TocPlugin.key]: TocElement,
                [TogglePlugin.key]: ToggleElement,
                [UnderlinePlugin.key]: withProps(PlateLeaf, { as: "u" }),
            }),
        },
        value: value,
        plugins: [...editorPlugins, FixedToolbarPlugin, FloatingToolbarPlugin],
    });
};
