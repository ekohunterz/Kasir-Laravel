import{r as h,v as b,o as n,c as y,w as r,e as l,t as e,a,d as c,F as V,f as x,g as C,b as d,u as i,C as A,n as B}from"./app-9cb26ff6.js";import{_ as $}from"./ActionMessage-dafe95ee.js";import{_ as S}from"./ActionSection-bb60ff24.js";import{_ as H}from"./DialogModal-9a2b4a3d.js";import{_ as L}from"./InputError-71f77be1.js";import{_ as g}from"./PrimaryButton-0fb1f193.js";import{_ as F}from"./SecondaryButton-4d541c38.js";import{_ as N}from"./TextInput-f97bd710.js";import"./SectionTitle-1f89132a.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./index-afa3307c.js";import"./Modal-d953ae84.js";const K={class:"max-w-xl text-sm text-slate-600 dark:text-slate-400"},M={key:0,class:"mt-5 space-y-6"},U={key:0,class:"w-8 h-8 text-slate-500",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},j=a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"},null,-1),D=[j],E={key:1,class:"w-8 h-8 text-slate-500",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},I=a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"},null,-1),O=[I],T={class:"ml-3"},z={class:"text-sm text-slate-600 dark:text-slate-400"},q={class:"text-xs text-slate-500"},G={key:0,class:"text-green-500 font-semibold"},J={key:1},P={class:"flex items-center mt-5"},Q={class:"mt-4"},ne={__name:"LogoutOtherBrowserSessionsForm",props:{sessions:Array},setup(p){const _=h(!1),m=h(null),s=b({password:""}),v=()=>{_.value=!0,setTimeout(()=>m.value.focus(),250)},w=()=>{s.delete(route("other-browser-sessions.destroy"),{preserveScroll:!0,onSuccess:()=>u(),onError:()=>m.value.focus(),onFinish:()=>s.reset()})},u=()=>{_.value=!1,s.reset()};return(o,f)=>(n(),y(S,null,{title:r(()=>[l(e(o.lang().label.browser_session),1)]),description:r(()=>[l(e(o.lang().label.browser_session_description),1)]),content:r(()=>[a("div",K,e(o.lang().label.browser_session_content),1),p.sessions.length>0?(n(),c("div",M,[(n(!0),c(V,null,x(p.sessions,(t,k)=>(n(),c("div",{key:k,class:"flex items-center"},[a("div",null,[t.agent.is_desktop?(n(),c("svg",U,D)):(n(),c("svg",E,O))]),a("div",T,[a("div",z,e(t.agent.platform?t.agent.platform:"Unknown")+" - "+e(t.agent.browser?t.agent.browser:"Unknown"),1),a("div",null,[a("div",q,[l(e(t.ip_address)+", ",1),t.is_current_device?(n(),c("span",G,e(o.lang().label.this_device),1)):(n(),c("span",J,e(o.lang().label.last_active)+" "+e(t.last_active),1))])])])]))),128))])):C("",!0),a("div",P,[d(g,{onClick:v},{default:r(()=>[l(e(o.lang().button.logout_other_browser_session),1)]),_:1}),d($,{on:i(s).recentlySuccessful,class:"ml-3"},{default:r(()=>[l(" Done. ")]),_:1},8,["on"])]),d(H,{show:_.value,onClose:u},{title:r(()=>[l(e(o.lang().label.logout_other_browser_session),1)]),content:r(()=>[l(e(o.lang().label.browser_session_caption)+" ",1),a("div",Q,[d(N,{ref_key:"passwordInput",ref:m,modelValue:i(s).password,"onUpdate:modelValue":f[0]||(f[0]=t=>i(s).password=t),type:"password",class:"mt-1 block w-full",placeholder:o.lang().placeholder.password,autocomplete:"current-password",onKeyup:A(w,["enter"]),error:i(s).errors.password},null,8,["modelValue","placeholder","onKeyup","error"]),d(L,{message:i(s).errors.password,class:"mt-2"},null,8,["message"])])]),footer:r(()=>[d(F,{onClick:u},{default:r(()=>[l(e(o.lang().button.cancel),1)]),_:1}),d(g,{class:B(["ml-3",{"opacity-25":i(s).processing}]),disabled:i(s).processing,onClick:w},{default:r(()=>[l(e(o.lang().button.logout_other_browser_session)+" "+e(i(s).processing?"...":""),1)]),_:1},8,["class","disabled"])]),_:1},8,["show"])]),_:1}))}};export{ne as default};
